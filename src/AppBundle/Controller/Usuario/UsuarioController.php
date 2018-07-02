<?php 


namespace AppBundle\Controller\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Service\serviceSerialize;

class UsuarioController extends Controller
{
	/**
	 *
	 * @Route("/usuario", name = "lista_usuario")
	 */
	public function indexUsuarios(){

		 $usuarios = $this->getDoctrine()
		 	->getRepository(Usuario::class)
		 	->findAll();
			return $this->render("@App/Usuario/lista_usuario.html.twig",["usuarios"=>$usuarios]);
	}

    /**
     *
     * @Route("/usuario/{id}", name = "editar_usuario")
     * @Method("GET")
     * @param Usuario $usuario
     */
    public function indexEditUsuario(Usuario $usuario){

        return $this->render("@App/Usuario/editar_usuario.html.twig",
            ["usuario"=>$usuario
            ]
        );
    }

    /**
     *
     * @Route("/usuario/{id}/", name = "eliminar_usuario")
     * @Method("DETELE")
     * @param Usuario $usuario
     */
    public function indexEliminarUsuario(Usuario $usuario){

        $em = $this->getDoctrine()->getManager();
        $em->remove($usuario);
        $em->flush();


        return $this->redirectToRoute("lista_usuario"
        );
    }
	/**
	 *
	 * @Route("/usuario/{idUsuario}", name = "informacion usuario")
	 */
	public function indexUsuariosid($idUsuario){
		return $this->render("@App/Usuario/index.html.twig",["idUsuario" => $idUsuario]
		);
	}


	//Restful API

	/**
	 *
	 * @Route("/rest/usuario/{id}", name = "buscar_usuarios")
	 * @Method("GET")
	 * @paramConverter("usuario", class="AppBundle:Usuario")
	 *@param Usuario $usuario
	 */
	public function buscarUsuarios($usuario){

		$jsonContent = new serviceSerialize();

		return $jsonContent->formatt($usuario);
	}

	/**
	 *
	 * @Route("/rest/usuario/", name = "guardar_usuarios")
	 * @Method("POST")
	 * @param Request $request
	 */
	public function guardarUsuarios(Request $request){
		$data = json_decode($request->getContent(), true);
		$usuario = new Usuario();
		$usuario->setNombre($data["nombre"]);
		$usuario->setUsername($data["username"]);
        $jsonContent=$this->get('serializer')->seriale($usuario,'json');
		$em = $this->getDoctrine()->getManager();
		//persist is to save
		$em->persist($usuario);

		$em->flush();
		$jsonContent=json_decode($jsonContent,true);

		return new  JsonResponse($jsonContent);
		//sformtype
		dump($data["nombre"]);
		die;
	}

	/**
	 *
	 * @Route("/rest/usuario/{id}", name = "actualizar_usuarios")
	 * @Method("PUT")
	 * @paramConverter("usuario", class="AppBundle:Usuario")
	 * @param Request $request
	 *@param Usuario $usuario
	 *return JsonResponse
	 */
	public function actualozarUsuarios(Request $request, $usuario){
		$data = $request->getContent();
		$data = json_decode($data, true);

		$usuario->setNombre($data["nombre"]);
		$usuario->setUsername($data["username"]);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $jsonContent=$this->get('serializer')->serialize($usuario,'json');
        $jsonContent=json_decode($jsonContent,true);



        return new  JsonResponse($jsonContent);
	}
}