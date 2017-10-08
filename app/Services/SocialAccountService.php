<?php
namespace App\Services;

use App\LinkedSocialAccount;
use App\User;

class SocialAccountService {

    public function findOrCreate($providerUser, $provider)
    {
        $account = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
        
        if ($account) {
            return $account->user;
        }else{

            $user = User::where('email', $providerUser->getEmail())->first();

            if ( ! $user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName()
                ]);
            }

            $user->accounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider
            ]);

            return $user;

        }
    }

}
