<?php
namespace App\Controller;

use Pitch\Liform\LiformInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LiformController extends AbstractController
{
    protected LiformInterface $liform;

    public function __construct(LiformInterface $liform)
    {
        $this->liform = $liform;
    }

    public function __invoke(Request $request)
    {
        $form = $this->createForm(FormType::class);

        $form->add('foo', TextType::class);
        $form->add('bar', NumberType::class);

        $form->handleRequest($request);

        $formParams = (array) $this->liform->transform($form->createView());
        $formParams['verboseFields'] = (bool) $request->query->get('verboseFields');

        return new Response(
            $this->renderView('form.html.twig', [
                'stylesheets' => $request->query->get('stylesheets'),
                'scripts' => $request->query->get('scripts'),
                'formParams' => [
                    'props' => $formParams,
                    'rendering' => 'both',
                ],
            ]),
            $form->isSubmitted() && !$form->isValid() ? 400 : 200,
        );
    }
}
