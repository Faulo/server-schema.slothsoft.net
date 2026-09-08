# AGENTS.md

Shared instructions for coding agents. Project-specific information is kept in [README.md](README.md), read it before non-trivial changes.

## Farah Module

This project is a farah module. Its assets lie in `assets`.

## PHP

### Environment and tools

Agents run directly on the host, not inside a Docker container. Commands, paths, and tools therefore use the host environment unless explicitly run through DDEV.

This is a DDEV project. The default DDEV `web` container defines the package's development runtime. `PHP_VERSION` in `.env` is authoritative.

All package code must be syntactically valid on the PHP version declared by `PHP_VERSION`, runnable in DDEV, and compatible with every PHP version supported by CI. Do not rely on syntax, extensions, binaries, or platform features available only on the host.

Host PHP may be used for ad hoc code execution and supporting work. Successful host-side execution does not validate package compatibility; use DDEV for validation that depends on the package runtime.

When executing ad hoc PHP code from the shell, do not use inline snippets such as `php -r`, especially under PowerShell. Write code to a temporary `.php` file and execute that file with `php`. Use project temp-file helpers inside PHP code; remove shell-created probe files after use when appropriate.

Run all Composer commands through DDEV with `ddev composer ...`. Do not run Composer directly on the host. Do not install persistent host dependencies. Use `npx` for one-off Node.js tools; do not run `npm install` on the host.

### PHPUnit

The PHPUnit config is `phpunit.xml`. Run all PHPUnit invocations, including filtered and targeted runs, in DDEV's default `web` container:

```bash
ddev exec vendor/bin/phpunit
```

Never run PHPUnit on the host. Start DDEV first when necessary. If an optional extension or platform feature is unavailable in DDEV, report which validation could not run and why. Treat skipped tests as intentional unless the task concerns test skipping.

Use `@runInSeparateProcess` for tests of APIs with global or static process state. When manually changing a test marked `@todo auto-generated`, remove the marker so the generator no longer treats it as disposable. Files in `test-files/` are canonical fixtures, not disposable output.

Tests may create temporary files through `temp_file`, `temp_dir`, or `Slothsoft\Core\IO\FileInfoFactory::createTempFile`; manual cleanup is not required for files managed by these helpers.

### MCP validation

When an IDE MCP server is available, use it after editing code to review changes and retrieve inspections for touched files. Fix relevant in-scope findings and report remaining ones. When a CI MCP server is available, use it to validate the exact SHA of every pushed agent-authored commit. An authorized push normally starts CI; do not trigger another build unless needed for diagnosis. Investigate relevant failures and report the job, commit SHA, and result. If changes are not pushed, report CI validation as pending. Do not push or trigger jobs without authorization.

### Documentation and style

The PHPDoc config is `phpdoc.xml`. Generate documentation in DDEV with `ddev exec vendor/bin/phpdoc`. `.editorconfig` is in effect.

## General

### Meta commands

These short messages have special handling when they appear alone in a user message:

- `ping`: Reply with `pong`.
- `.`: Reply with `.`.
- `?`: Continue the previous response or task after an interruption.
- `ticket <URI>`: Read the linked ticket and all comments through the available integration. Inspect the project, reproduce the current behavior, and run relevant checks as needed. Then explain the request, project context, reproducibility, risks, and a proposed implementation plan. Do not edit files, change remote state, commit, or push until the user approves the approach.
- `can you <x>?` is a question about your knowledge, capabilities or permissions. It is not an instruction to perform `x`.

### Compatibility

Follow semantic versioning. Preserve backward compatibility for public APIs unless the task explicitly permits a breaking change.

### Project conventions

`.editorconfig` is authoritative. Never edit `.editorconfig` unless expressly instructed by the user.

### Git

Git mutations are forbidden by default. Agents may use read-only inspection commands such as `git status`, `git log`, `git diff`, `git show`, `git blame`, and `git branch --list` without additional permission.

An agent may perform Git mutations only after the user explicitly opts in. Permission is limited to the operations and task the user authorized; do not treat prior authorization as standing permission for later mutations.

When Git mutations are authorized:

- The user is responsible for choosing the branch. Verify the current branch and working-tree status before editing and again before creating commits.
- Treat all unknown local changes as user work. Do not overwrite, stage, commit, restore, or otherwise alter them.
- Keep commits small and cohesive.
- Format agent-authored commits according to Conventional Commits 1.0.0: `<type>[optional scope]: <description>`.
- When working from a ticket, include the ticket key and URL in the commit footer.
- Before committing, read the configured Git author name and email. Keep the configured email, append the agent name once, in brackets to the configured author name (e.g. `Daniel Schulz (Codex)`), and pass that identity explicitly with `git commit --author`. Do not modify repository or global Git configuration.
- Do not force-push, amend, rebase, reset, or discard changes unless the user explicitly requests that specific operation.

### CI Infrastructure

Jenkins runs at `https://ci.slothsoft.net/`. Infrastructure reads are always permitted; triggering builds or making any other change requires explicit user consent.

Use these access methods:

- `ssh ci.slothsoft.net <command>` invokes the Jenkins CLI directly; it does not open a host shell. Useful read-only commands include `who-am-i`, `list-jobs`, and `console`.
- `ssh <host>` opens that server's shell.
- `docker --context <host> ...` talks to that server's Docker daemon. Always name the context explicitly.

The three CI servers are:

- `groke`: Ubuntu; runs the Jenkins controller and a Jenkins agent in the `agents_jenkins-agent` container. SSH alias and Docker context: `groke`.
- `garl`: Ubuntu; runs a Jenkins agent in the `agents_jenkins-agent` container, connected to the controller on `groke`. SSH alias and Docker context: `garl`.
- `dende`: Windows Server 2019; runs a Jenkins agent in the `agents_jenkins-agent` container, connected to the controller on `groke`. SSH alias and Docker context: `dende`.
