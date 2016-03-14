<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\LessonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;

use ApiBundle\Exception\InvalidFormException;
use ApiBundle\Model\LessonInterface;


class LessonController extends FOSRestController
{
    /**
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing lessons.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many lessons to return.")
     *
     * @Annotations\View(
     *  templateVar="lessons"
     * )
     *
     * @param Request $request the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getLessonsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('api.lesson.handler')->all($limit, $offset);
    }

    /**
     * @Annotations\View(templateVar="lesson")
     *
     * @param int $id the lesson id
     *
     * @return array
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function getLessonAction($id)
    {
        $lesson = $this->getOr404($id);
        return $lesson;
    }

    /**
     * Presents the form to use to create a new lesson.
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newLessonAction()
    {
        return $this->createForm(new LessonType());
    }

    /**
     * Create a Lesson from the submitted data.
     *
     * @Annotations\View(
     *  template = "ApiBundle:Lesson:newLesson.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postLessonAction(Request $request)
    {
        try {
            $newLesson = $this->container->get('api.lesson.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newLesson->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_get_lesson', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing lesson from the submitted data or create a new lesson at a specific location.
     *
     * @Annotations\View(
     *  template = "ApiBundle:Lesson:editLesson.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int $id the lesson id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function putLessonAction(Request $request, $id)
    {
        try {
            if (!($lesson = $this->container->get('api.lesson.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $lesson = $this->container->get('api.lesson.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $lesson = $this->container->get('api.lesson.handler')->put(
                    $lesson,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $lesson->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_get_lesson', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing lesson from the submitted data or create a new lesson at a specific location.
     *
     * @Annotations\View(
     *  template = "ApiBundle:Lesson:editLesson.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int $id the lesson id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function patchLessonAction(Request $request, $id)
    {
        try {
            $lesson = $this->container->get('acme_blog.page.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $lesson->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_get_lesson', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }
    
    /**
     * Fetch a Lesson or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return LessonInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($lesson = $this->container->get('api.lesson.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }
        return $lesson;
    }
}
