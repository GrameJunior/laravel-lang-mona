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
