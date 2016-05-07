<?php
/**
 * Contact form for Sillonbol.com
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $captchaSettings = array(
            'width'=>206,
            'height'=>57,
            'font_size'=>22,
            'length'=>7,
            'border_color'=>"cccccc"
        );
        $builder
            ->add( 'name', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Tu nombre...',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('email', 'email', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Tu email...'
                )
            ))
            ->add('message', 'textarea', array(
               
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => '¿Qué te cuentas?'
                )
            ))
            ->add('securitycod', 'genemu_captcha', $captchaSettings)
            ->add('Enviar', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'El nombre es obligatorio.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'El email no es correcto.')),
                new Email(array('message' => 'Email incorrecto.'))
            ),
            'message' => array(
                new NotBlank(array('message' => 'No admitimos mensajes en blanco.')),
                new Length(array('min' => 5))
            ),
            'securitycod' => array()
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }
    
    public function getName()
    {
        return 'contacto';
    }

}
