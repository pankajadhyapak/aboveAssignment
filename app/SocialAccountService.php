<?php

namespace App;

use App\Notifications\UserRegistered;
use Laravel\Socialite\Contracts\Provider;

class SocialAccountService
{

    /**
     * Create or get the user from Social Account 
     * @param Provider $provider
     * @return User
     */
    public function createOrGetUser(Provider $provider)
    {
        $providerUser = $provider->user();
        $providerName = class_basename($provider);

        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName,
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $password = str_random(6);
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'password' => bcrypt($password)
                ]);
                $user->notify(new UserRegistered($user, $password));
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}