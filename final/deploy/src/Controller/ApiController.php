<?php

namespace App\Controller;

use App\Entity\User;
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

use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;

/**
 * Class ApiController
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{

    /**
     * @Rest\Post("/login_check", name="user_login_check")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User was logged in successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not logged in successfully"
     * )
     *
     * @SWG\Parameter(
     *     name="_username",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_password", in="body",
     *     type="string",
     *     description="The password",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function loginCheckAction() {}

    /**
     * @Rest\Post("/register", name="user_register")
     *
     * @SWG\Response(
     *     response=201,
     *     description="User was successfully registered"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not successfully registered"
     * )
     *
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="The password"
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
 
        $user = [];
        $message = "";
 
        try {
            $code = 200;
            $error = false;
 
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');
 
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setPassword($encoder->encodePassword($user, $password));
            $user->setFirstName("fulanito");
            $user->setLastName("test");
            $user->setActivo(true);

 
            $em->persist($user);
            $em->flush();
 
        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to register the user - Error: {$ex->getMessage()}";
        }
 
        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $user : $message,
        ];
 
        return new Response($serializer->serialize($response, "json"));
    }

     /**
     * @Rest\Get("/session", name="user_session")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Session retrived"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Session was not successfully retrived"
     * )
     *
     *
     * @SWG\Tag(name="User")
     */
    public function sessionRetriver(Request $request) {
        $serializer = $this->get('jms_serializer');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $this->getPermisos($user);

        return new Response($serializer->serialize($user, "json"));
    }

    /**
     * @Rest\Post("/test", name="test_test")
     * 
     * @RequestParam(name="pepe", strict=true, nullable=false, allowBlank=false)
     * @param ParamFetcher $pf
     * 
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Test")
     */
    public function testRoute(ParamFetcher $pf){
        $serializer = $this->get('jms_serializer');


        dd($pf->get('pepe'));

        $response = [
            'code' => 200,
        ];
 
        return new Response($serializer->serialize($response, "json"));


    }

    //agrega los permisos a cada rol del usuario
    protected function getPermisos($u)
    {
        $emr = $this->getDoctrine()->getRepository(RolesDelSistema::class);
        $ro = $u->getRoles();
        $roles_con_permisos = Array();
        foreach($ro as $u_r){
            $r = $emr->findOneBy(
                ['nombre' => $u_r]
            );
            array_push($roles_con_permisos,$r);
        }
        $u->setRoles($roles_con_permisos);
    }

}
