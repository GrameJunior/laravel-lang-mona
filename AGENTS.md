## Git Commits

- Always write Git commit messages in English, regardless of the conversation language.
- This requirement applies to both commit subjects and bodies.
- Use the `aif-commit` skill when creating commits.
- Keep it concise. Skip unnecessary details.
- Review the diff first, then compose the message.
- Use a single-line header only; do not add a body.
- Be as brief as you can.
- Aim for up to 70 characters (including any markup).
- Absolute cap: 100 characters for the header (including any markup).
- Do not end the header line with a dot.
- Enclose class names, variables, commands and attributes in backticks `like_this`. Example: Changed the type of `$foo` in class `Bar`.
- Start the header with an action verb that fits the change: added, changed, updated, fixed, improved, translated, removed, deleted, etc.
- Describe the direct edits made to the code, not their outcomes or effects.
- For class names and namespaces, omit leading and trailing backslashes.
- For changes in the `docs` folder or `README.md`, always prefix the header with `[docs]: `.
- For changes in the `src/*/source` folder, always prefix the header with `[source]: `.
- For changes in the `src/*/locales` folder, always prefix the header with the affected locale subfolder names using `[<locales>]: `, e.g., `[fr]: `, `[de,zh_CN]: `, etc.
- For changes in all other folders, always prefix the header with `[app]: `.


## Testing

- When writing tests, verify the feature's observable behavior through its normal entry points.
- Unit tests are optional.


## Task Coordination and Acceptance

- Study the task specification and requirements before starting work.
- Create a work plan, split it into tasks, and assign them to separate chats.
- Thoroughly review each chat's completed work against the specification and plan.
- Return work with defects, inconsistencies, or deviations to the responsible chat for revision.
- Accept the work only after verifying that all identified issues have been resolved and the requirements are met.


## Subagents

Use subagents selectively.
Delegate work only when the task contains substantial independent workstreams that can be executed in parallel and
delegation is expected to materially improve quality or reduce completion time.
Keep short tasks, routine repository exploration, sequential work, and tasks that depend heavily on shared context in
the current agent.
Avoid delegating work that would require a subagent to reconstruct most of the parent agent's context.
Prefer completing the task directly when delegation provides only a marginal benefit.
When delegating work, use the configured default subagent model and reasoning effort. Do not use a stronger model or
higher reasoning effort for a subagent unless the delegated task clearly requires it.
Do not use GPT-6 Astra for subagents unless explicitly requested by the user.
Subagents should not create additional subagents by default. They may do so only when their assigned task itself
contains substantial independent workstreams and further delegation provides a clear material benefit.
Do not delegate merely to perform independent verification, obtain a second opinion, or re-check work that the current
agent can reasonably verify itself. Delegate verification only when the task is sufficiently complex or high-risk that
an independent context materially improves reliability.
Messages that you send to other agents and your final answer may be read by a human, so ensure they are legible. Always
put proper spaces between words and/or numbers.
