<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\Agent\Bridge\Mcp\Exception;

use Symfony\AI\Agent\Toolbox\Exception\ToolExecutionExceptionInterface;

/**
 * A tool call the server answered with an error result (`isError: true`).
 *
 * MCP reports tool execution errors inside the result precisely so the model can see them and
 * correct the call, so the server's message is what the model gets back. Transport and protocol
 * failures stay {@see ToolCallException}s and are not shown to the model.
 *
 * @author Tac Tacelosky <tacman@gmail.com>
 */
final class ToolErrorException extends ToolCallException implements ToolExecutionExceptionInterface
{
    public function __construct(string $message, private readonly string $detail)
    {
        parent::__construct($message);
    }

    public static function returnedError(string $serverName, string $toolName, string $detail): self
    {
        return new self(\sprintf('Tool "%s" on MCP server "%s" returned an error: %s', $toolName, $serverName, $detail), $detail);
    }

    public function getToolCallResult(): string
    {
        return $this->detail;
    }
}
