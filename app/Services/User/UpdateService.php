<?php
namespace App\Services\User;

class UpdateService
{
    public function update_profile($request)
    {
        $user = auth()->user()->load('addresses');
        $this->update_user($request, $user);
        $this->create_or_update_address($request, $user);

        return $user;
    }
    protected function update_user($request, $user)
    {
        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
            'avatar' => $request->avatar ?? $user->avatar,
        ]);
    }

    protected function create_or_update_address($request, $user)
    {
        $address = $user->addresses()->where('type', $request->address['type'])->first();

        if ($request->has('address')) {

            if ($address) {
                $address->update([
                    'city' => $request->address['city'],
                    'country' => $request->address['country'],
                    'street' => $request->address['street'],
                    'state' => $request->address['state'],
                    'post_code' => $request->address['post_code'],
                ]);
            } else {
                $user->addresses()->create([
                    'type' => $request->address['type'],
                    'city' => $request->address['city'],
                    'country' => $request->address['country'],
                    'street' => $request->address['street'],
                    'state' => $request->address['state'],
                    'post_code' => $request->address['post_code'],
                ]);
            }
        }
    }
}