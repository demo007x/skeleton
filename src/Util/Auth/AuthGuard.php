<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Bclfp\Skeleton\Util\Auth;

interface AuthGuard
{
    public function token(Authenticatable $user);

    public function user(): ?Authenticatable;

    public function check(): bool;

    public function guest(): bool;

    public function logout();

    public function getProvider(): UserProvider;

    // public function getName(): string;
}
