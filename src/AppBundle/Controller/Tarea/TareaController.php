<?php 


namespace AppBundle\Controller\Tarea;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// use AppBundle\Entity\Usuario;
class TareaController extends Controller
{
	/**
	 *
	 * @Route("/tarea", name = "lista_tarea")
	 */
	public function indexTareas(){
		return $this->render("@App/Tarea/lista_tareas.html.twig");
	}
}