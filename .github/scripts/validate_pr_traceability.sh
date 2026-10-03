#!/usr/bin/env bash
set -euo pipefail

base="${1:?base commit is required}"
head="${2:?head commit is required}"
pattern='NN-[0-9]+'

declared="$(printf '%s\n%s\n%s\n' "${PR_TITLE:-}" "${PR_BODY:-}" "${PR_HEAD_REF:-}" | grep -Eo "$pattern" | sort -u || true)"
[ -n "$declared" ] || {
  echo 'PR must declare at least one NN in its title, body or branch.' >&2
  exit 1
}

actual=''
untraceable=''
while IFS= read -r commit; do
  [ -n "$commit" ] || continue
  [ "$(git show -s --format='%P' "$commit" | wc -w | tr -d ' ')" -le 1 ] || continue
  keys="$(git show -s --format='%s%n%b' "$commit" | grep -Eo "$pattern" | sort -u || true)"
  if [ -z "$keys" ]; then
    untraceable="${untraceable}${commit}\n"
  else
    actual="${actual}${keys}\n"
  fi
done < <(git rev-list --reverse "$base..$head")

[ -z "$untraceable" ] || {
  echo 'PR contains commits without NN traceability:' >&2
  printf '%b' "$untraceable" | sed '/^$/d; s/^/  - /' >&2
  exit 1
}

undeclared="$(comm -23 \
  <(printf '%b' "$actual" | sed '/^$/d' | sort -u) \
  <(printf '%s\n' "$declared" | sed '/^$/d' | sort -u))"
[ -z "$undeclared" ] || {
  echo 'PR contains NN items outside its declared scope:' >&2
  printf '%s\n' "$undeclared" | sed 's/^/  - /' >&2
  exit 1
}

printf 'PR traceability validated for: %s\n' "$(printf '%s' "$declared" | paste -sd, -)"
