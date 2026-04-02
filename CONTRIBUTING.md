# Guia de Contribuição

Obrigado por considerar contribuir com o **My Invoice**.

## Como contribuir

1. Faça um fork do repositório.
2. Crie uma branch descritiva:

```bash
git checkout -b feat/minha-melhoria
```

3. Implemente sua alteração com foco em clareza e consistência.
4. Rode validações locais.
5. Abra um Pull Request com contexto claro.

## Padrões esperados

- Código simples e objetivo.
- Nomes de classes, métodos e variáveis claros.
- Evitar mudanças grandes sem justificativa.
- Não incluir segredos, chaves ou credenciais.

## Checklist antes do PR

Execute, no mínimo:

```bash
docker compose exec phpi php bin/console cache:clear
docker compose exec phpi php bin/console lint:twig templates
docker compose exec phpi php bin/console doctrine:migrations:migrate --no-interaction
```

Se houver alteração de schema, inclua migration.

## Como abrir um bom PR

Inclua no PR:

- Objetivo da mudança
- O que foi alterado
- Como testar
- Prints/GIF quando houver alteração de UI

## Escopo para issues

Bons exemplos:

- Bug reproduzível com passos claros
- Melhoria com justificativa funcional
- Sugestão com impacto técnico descrito

## Código de conduta

- Respeito nas discussões
- Feedback técnico e objetivo
- Sem ataques pessoais
