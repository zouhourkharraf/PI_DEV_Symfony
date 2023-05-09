<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_rec')
            ->add('type_rec')
            ->add('date_rec')
            ->add('contenu_rec')
            ->add('statut_rec')
            ->add('username')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
    //sms
    public  function sms(){
        // Your Account SID and Auth Token from twilio.com/console
                $sid = 'AC9cbf3d245cc90ddb31db6e6edd046fd1';
                $auth_token = '386f12af78bc83af84023dde83fbe8f2';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
                $twilio_number = "+16073576523";
        
                $client = new Client($sid, $auth_token);
                $client->messages->create(
                // the number you'd like to send the message to
                    '+21696869820',
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => '+21696869820',
                        // the body of the text message you'd like to send
                        'body' => 'votre reclamation a été traité merci de nous contacter pour plus de détail!'
                    ]
                );
            }
}
