<?php
namespace App\Controller;

use Pitch\Liform\LiformInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // Create a form with two fields
        $form = $this->createForm(FormType::class);
        $form->add('foo', TextType::class);
        $form->add('bar', NumberType::class);

        if ($request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : []);
        }

        $form->handleRequest($request);

        $view = $form->createView();

        $formProps = (array) $this->liform->transform($view);
        
        // set the name for the root view
        $formProps['name'] = $view->vars['full_name'];

        $formProps['verboseFields'] = (bool) $request->query->get('verboseFields');

        $statusCode = $form->isSubmitted() && !$form->isValid() ? 400 : 200;

        if ($this->isJsonPreferred($request)) {
            return new JsonResponse($formProps, $statusCode);
        }

        return new Response(
            $this->renderView('form.html.twig', [
                'stylesheets' => $request->query->get('stylesheets'),
                'scripts' => $request->query->get('scripts'),
                'formParams' => [
                    'props' => $formProps,
                    'rendering' => 'both',
                ],
            ]),
            $statusCode,
        );
    }

    private function isJsonPreferred(
        Request $request
    ): bool {
        foreach ($request->getAcceptableContentTypes() as $type) {
            if ($type === 'application/json') {
                return true;
            } elseif ($type === 'text/html') {
                return false;
            } elseif ($type === '*/*') {
                return $request->isXmlHttpRequest()
                    || $request->headers->get('content-type') === 'application/json'
                ;
            }
        }
    }
}
