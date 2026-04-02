# Política de Segurança

Agradecemos o reporte responsável de vulnerabilidades.

## Como reportar

Se você identificar uma vulnerabilidade, **não abra issue pública**.

Envie um e-mail com detalhes para:

- `security@exemplo.com` (substituir pelo e-mail oficial do mantenedor)

Enquanto o e-mail oficial não for definido, use contato direto com o mantenedor do projeto.

## O que incluir no reporte

- Descrição clara do problema
- Passos para reprodução
- Impacto potencial
- Vetor de ataque
- Sugestão de correção (opcional)

## Tempo de resposta esperado

- Confirmação de recebimento: até 5 dias úteis
- Primeira análise: até 10 dias úteis
- Correção e divulgação coordenada: conforme severidade

## Práticas mínimas recomendadas

- Nunca versionar segredos
- Rotacionar credenciais expostas
- Validar entradas de formulário
- Restringir acesso por usuário dono dos dados
- Manter dependências atualizadas

## Escopo

Abrange:

- Código da aplicação
- Fluxo de autenticação/autorização
- Processamento de invoice/PDF
- Fluxo de envio de e-mail

Não abrange:

- Ambientes de terceiros sem gestão do projeto
- Configurações locais inseguras do próprio usuário
