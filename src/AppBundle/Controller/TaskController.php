<?php

namespace AppBundle\Controller;

use AppBundle\Security\SecurityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Form\TaskType;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends SecurityController
{
    /**
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

        return ['item' => $entity];
    }
    /**
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {

        $user = $this->getUser();

        $results = $this->getDoctrine()->getRepository('AppBundle:Task')
            ->findBy(['user' => $user->getId()]);

        return ['items' => $results];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getAction(int $id)
    {
        $user = $this->getUser();

        return ['item' => $this->findTask($user, $id)];
    }

    /**
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

        return [];
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

        if (!$task->getId()) {
            throw new BadRequestHttpException('Task not found.');
        }

        return $task;
    }


}
