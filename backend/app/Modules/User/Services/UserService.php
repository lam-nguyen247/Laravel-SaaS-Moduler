<?php

namespace App\Modules\User\Services;

use App\Enums\UserStatus;
use App\Models\PasswordReset;
use App\Models\SocialAccount;
use App\Models\User;
use App\Modules\User\Repositories\PasswordResetRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Services\AbstractService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends AbstractService
{
    protected PasswordResetRepository $passwordResetRepository;

    public function __construct(UserRepository $userRepository, PasswordResetRepository $passwordResetRepository)
    {
        $this->repository = $userRepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    /**
     * Get token user when login
     *
     * @param array $data
     *
     * @return ?string
     */
    public function verifyLogin(array $data): ?string
    {
        return auth('front-api')->attempt(array_merge($data, ['status' => UserStatus::ACTIVE]));
    }

    /**
     * Change Password
     *
     * @param User  $user
     * @param array $data
     *
     * @return bool
     */
    public function changePassword(User $user, array $data): bool
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            return false;
        }

        $response = $this->update($user, [
            'password' => $data['new_password'],
        ]);

        return $response instanceof User;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function forgotPassword(string $email): bool
    {
        $user = $this->repository->findOneBy(
            [
                'email' => $email,
                'status' => UserStatus::ACTIVE,
            ]
        );

        if (!$user instanceof User) {
            return false;
        }

        $passwordReset = $this->passwordResetRepository->updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);

        return $passwordReset instanceof PasswordReset;
    }

    /**
     * Reset password
     *
     * @param string $email
     * @param string $token
     * @param string $newPassword
     *
     * @return bool
     * @throws Exception
     */
    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        try {
            return DB::transaction(function () use ($email, $token, $newPassword) {
                $passwordReset = $this->passwordResetRepository->findOneBy([
                    'email' => $email,
                    'token' => $token,
                ]);

                if (!$passwordReset instanceof PasswordReset) {
                    return false;
                }

                $user = $this->repository->findOneBy(['email' => $email]);

                $this->repository->update($user, [
                    'password' => $newPassword,
                ]);

                $this->passwordResetRepository->delete($passwordReset);

                return true;
            });
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception();
        }
    }

    /**
     * handle callback social login
     *
     * @param string $provider
     *
     * @return ?string
     */
    public function handleCallbackSocial(string $provider): ?string
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $account = SocialAccount::where([
            'provider_name' => $provider,
            'provider_id' => $socialUser->getId(),
        ])->first();

        if ($account?->user?->status === UserStatus::INACTIVE) {
            return null;
        }

        if ($account instanceof SocialAccount) {
            $token = JWTAuth::fromUser($account->user);

            return $token;
        }

        $user = $this->repository->findOneBy(['email' => $socialUser->getEmail()]);

        if (!$user instanceof User) {
            $user = User::create([
                'email' => $socialUser->getEmail(),
                'first_name' => $socialUser->getName(),
                'status' => UserStatus::ACTIVE,
            ]);
        }

        $user->socialAccounts()->create([
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
        ]);

        return JWTAuth::fromUser($user);
    }
}
