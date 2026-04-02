param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$Version,

    [Parameter(Mandatory = $false)]
    [switch]$AllowDirty
)

$ErrorActionPreference = "Stop"

$ComposeFile = if ($env:COMPOSE_FILE) { $env:COMPOSE_FILE } else { "compose.yaml" }
$PhpService = if ($env:PHP_SERVICE) { $env:PHP_SERVICE } else { "phpi" }
$UpgradeBranch = "upgrade/$Version"

function Assert-Command {
    param([string]$Name)

    if (-not (Get-Command $Name -ErrorAction SilentlyContinue)) {
        throw "Erro: comando '$Name' não encontrado."
    }
}

function Invoke-Compose {
    param([string[]]$ComposeArgs)
    & docker compose -f $ComposeFile @ComposeArgs
}

function Invoke-Php {
    param([string[]]$PhpArgs)
    Invoke-Compose -ComposeArgs (@("exec", $PhpService) + $PhpArgs)
}

Assert-Command -Name git
Assert-Command -Name docker

$null = & docker compose version 2>$null
if ($LASTEXITCODE -ne 0) {
    throw "Erro: 'docker compose' não está disponível. Atualize Docker Desktop/Compose v2."
}

if (-not (Test-Path ".git")) {
    throw "Erro: execute este script na raiz do repositório Git."
}

if (-not $AllowDirty) {
    $status = git status --porcelain
    if (-not [string]::IsNullOrWhiteSpace($status)) {
        throw "Erro: há alterações locais não commitadas. Faça commit/stash ou use -AllowDirty."
    }
}

Write-Host ">>> Buscando tags remotas..."
git fetch --tags origin

$null = git rev-parse -q --verify "refs/tags/$Version" 2>$null
if ($LASTEXITCODE -ne 0) {
    throw "Erro: tag '$Version' não encontrada."
}

$null = git show-ref --verify --quiet "refs/heads/$UpgradeBranch" 2>$null
if ($LASTEXITCODE -eq 0) {
    throw "Erro: branch '$UpgradeBranch' já existe. Use outra versão ou delete a branch local existente."
}

Write-Host ">>> Criando branch $UpgradeBranch a partir da tag $Version..."
git checkout -b $UpgradeBranch "tags/$Version"

Write-Host ">>> Subindo/atualizando containers..."
Invoke-Compose -ComposeArgs @("up", "-d", "--build")

Write-Host ">>> Instalando dependências..."
Invoke-Php -PhpArgs @("composer", "install", "--no-interaction")

Write-Host ">>> Executando migrations..."
Invoke-Php -PhpArgs @("php", "bin/console", "doctrine:migrations:migrate", "--no-interaction")

Write-Host ">>> Compilando assets..."
Invoke-Php -PhpArgs @("php", "bin/console", "asset-map:compile")

Write-Host ">>> Limpando cache..."
Invoke-Php -PhpArgs @("php", "bin/console", "cache:clear")

Write-Host ""
Write-Host "Upgrade concluído para $Version."
Write-Host "Branch atual: $UpgradeBranch"
