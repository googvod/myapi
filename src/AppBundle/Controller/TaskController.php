<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Form\TaskType;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends BaseController
{

    /**
     * @ApiDoc(
     *     section="Task",
     *     description="Crete/Update task",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer key",
     *             "required"=true
     *         }
     *     },
     *     parameters={
     *         {"name"="content", "dataType"="string", "required"=true, "description"="content"},
     *         {"name"="completed", "dataType"="integer", "required"=false, "description"="is completed 0/1"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Token not found or expired",
     *         400="Bad Request"
     *      }
     * )
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    public function editAction(Request $request, int $id = null)
    {
        $user = $this->getUser();
        $task = $id ? $this->findTask($user, $id) : new Task();

        $form = $this->createForm(TaskType::class, $task, ['csrf_protection' => false]);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new BadRequestHttpException((string) $form->getErrors(true, false));
        }

        $entity = $form->getData();
        if (!$id) {
            $entity->setUser($user->getId())
                ->setCreatedAt();
        }

        $erm = $this->getDoctrine()->getManager();
        $erm->persist($entity);
        $erm->flush();

        return $this->view(['item' => $entity]);
    }
    /**
     * @ApiDoc(
     *     section="Task",
     *     description="Get tasks list",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer key",
     *             "required"=true
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Token not found or expired"
     *     }
     * )
     *
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {

        $user = $this->getUser();

        $results = $this->getDoctrine()->getRepository('AppBundle:Task')
            ->findBy(['user' => $user->getId()]);

        return $this->view(['items' => $results]);
    }

    /**
     * @ApiDoc(
     *     section="Task",
     *     description="Get task",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer key",
     *             "required"=true
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Token not found or expired",
     *         400="Bad Request"
     *     }
     * )
     *
     * @param int $id
     * @return array
     */
    public function getAction(int $id)
    {
        $user = $this->getUser();

        return $this->view(['item' => $this->findTask($user, $id)]);
    }

    /**
     * @ApiDoc(
     *     section="Task",
     *     description="Delete task",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer key",
     *             "required"=true
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Token not found or expired",
     *         400="Bad Request"
     *     }
     * )
     * @param int $id
     * @return array
     */
    public function deleteAction(int $id)
    {
        $user = $this->getUser();
        $task = $this->findTask($user, $id);

        $erm = $this->getDoctrine()->getManager();
        $erm->remove($task);
        $erm->flush();

        return $this->view(['massege' => 'Entity deleted']);
    }

    /**
     * @param User $user
     * @param int $id
     * @return Task
     */
    private function findTask(User $user, int $id)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')
            ->findOneBy(array('id' => $id, 'user' => $user->getId()));

        if (!$task) {
            throw new BadRequestHttpException('Task not found.');
        }

        return $task;
    }
}
