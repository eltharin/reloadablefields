<?php

namespace Eltharin\ReloadableFieldBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class BlockReloadSubmitFormExtension extends AbstractTypeExtension
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formError = new FormError('relaod form');
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use($formError): void {
                if($event->getForm()->getRoot()->getErrors()->count() == 0 && $this->requestStack->getMainRequest()->headers->get('X-reload-form') == 1)
                {
                    $event->getForm()->getRoot()->addError($formError);
                }
            }
        );
    }
    
    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }
}