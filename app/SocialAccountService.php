<?php 
namespace App;
use App\Rol;
use Synfony\Component\DomCrawler\Imagen;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
	public function createOrGetUser(ProviderUser $providerUser){

		$account=SocialAccount::whereProvider('google')
			->whereProviderUserId($providerUser->getId())->get()->first();

		\Session::set('avatar', $providerUser->getAvatar());

		if($account){
			return $account->user;
		}else {
			$account =new SocialAccount([
						'provider_user_id'=>$providerUser->getId(),
						'provider'=>'google'
				]);

			$user = User::whereEmail($providerUser->getEmail())->first();

			if(!$user){
				$user = User::create([
						'email'=>$providerUser->getEmail(),
						'name'=>$providerUser->getName(),
						'roles_id'=>Rol::usuario(),

					]);
				//recupera 
				$img= $providerUser->getAvatar();
		        $filename  = str_random(30) . '.'.'jpg';
		       \Storage::put('public/avatars/'.$filename, file_get_contents($img));
		       //Separa el nombre del apellido
		       	$posnombre2= 0;
		       	$posnombre3= 0;
		       	$apellido= '';
		       	$nombreCompleto= '';
		       	$longitud = (strlen($providerUser->getName()))-1;

		      	$pos = strpos($providerUser->getName(), ' ');
        		$nombre = substr($providerUser->getName(), 0,$pos);
        		$nombreCompleto= $nombre;
    			if ($longitud > $pos){
    				$posnombre2 = strpos($providerUser->getName(), ' ') ;
    				$nombre2 = substr($providerUser->getName(), $pos+1,$posnombre2);
    				if (($longitud <= $posnombre2+2)&&($posnombre2>0)){
	    					$apellido= $nombre2;
	    			}
	    			else{
	    				$nombreCompleto= $nombreCompleto.' '.$nombre2;
	    				$apellido = substr($providerUser->getName(), $posnombre2+$pos+1,$longitud);
		    		}
    			}

				Usuario::create([
		            'nombre' => $nombreCompleto,
		            'apellido' => $apellido,
		            'user_id' => $user->id,
		            'avatar' => $filename,
		            ]);     
			}
			

			$account->user()->associate($user);
			$account->save();
			return $user;
		}
	}
}