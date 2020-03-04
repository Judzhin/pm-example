<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works\Projects\Project\Setting;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
use App\UseCase\Work\Project\Department\Create;
use App\UseCase\Work\Project\Department\Edit;
use App\UseCase\Work\Project\Department\Remove;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentsController
 * @package App\Controller\Works\Projects\Project\Setting
 *
 * @Route("/work/project/{project_id}/setting/departaments", requirements={"id"=UUIDv4::PATTERN})
 * @ParamConverter("project", options={"id" = "project_id"})
 * @IsGranted("ROLE_WORK_MANAGE_PROJECTS")
 */
class DepartmentsController extends AbstractController
{
    /**
     * @Route("", name="pm_work_project_setting_departments")
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('works/projects/project/setting/departments/index.html.twig', compact('project'));
    }

    /**
     * @Route("/create", name="pm_work_project_setting_department_create")
     *
     * @param Project $project
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function create(Project $project, Request $request, Create\Handler $handler): Response
    {
        /** @var Create\Command $command */
        $command = new Create\Command;
        $command->project = $project;

        /** @var FormInterface|Create\FormType $form */
        $form = $this->createForm(Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            return $this->redirectToRoute('pm_work_project_setting_departments', [
                'project_id' => $project->getId()
            ]);
        }

        return $this->render('works/projects/project/setting/departments/create.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pm_work_project_setting_department_edit")
     *
     * @param Project\Department $department
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     * @throws \Throwable@Route("/{id}/edit", name="pm_work_project_setting_department_edit")
     */
    public function edit(Project\Department $department, Request $request, Edit\Handler $handler): Response
    {
        /** @var Edit\Command $command */
        $command = Edit\Command::parse($department);

        /** @var FormInterface|Edit\FormType $form */
        $form = $this->createForm(Edit\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())  {
            $handler->handle($command);
            return $this->redirectToRoute('pm_work_project_setting_departments', [
                'project_id' => $department->getProject()->getId()
            ]);
        }

        return $this->render('works/projects/project/setting/departments/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $department->getProject(),
            'department' => $department
        ]);
    }

    /**
     * @Route("/delete", name="pm_work_project_setting_department_delete", methods={"POST"})
     *
     * @param Project $project
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function delete(Project $project, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('pm_work_project_setting', ['id' => $project->getId()]);
        }

        // $this->denyAccessUnlessGranted(ProjectAccess::EDIT, $project);

//        /** @var Remove\Command $command */
//        $command = new Remove\Command($project->getId()->toString());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            // $this->errors->handle($e);
//            // $this->addFlash('error', $e->getMessage());
//        }

        return $this->redirectToRoute('pm_work_project_setting_departments');
    }
}