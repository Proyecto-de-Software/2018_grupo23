<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Entity\Genero;
use App\Entity\Permiso;
use App\Entity\RolesDelSistema;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class Paciente   Controller
 *
 * @Route("/paciente")
 */
class PacienteController extends FOSRestController
{


    /**
     *@Route("/index", name="paciente_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Patient")
     */
    public function index(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $patients = $this->getDoctrine()->getRepository(Paciente::class)->findAll();
        return new Response($serializer->serialize($patients, "json"));
    }
    

}
