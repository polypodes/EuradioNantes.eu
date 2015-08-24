<?php

namespace RadioSolution\ProgramBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as SonataCRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends SonataCRUDController
{

    public function batchActionPublish(ProxyQueryInterface $selectedModelQuery)
    {
        if (!$this->admin->isGranted('EDIT') || !$this->admin->isGranted('DELETE'))
        {
            throw new AccessDeniedException();
        }

        $selectedModels = $selectedModelQuery->execute();

        if ($this->changePublicationAction($selectedModels, true)) {
            $this->addFlash('sonata_flash_success', 'flash_batch_publish_success');
        } else {
            $this->addFlash('sonata_flash_error', 'flash_batch_publish_error');
        }

        return new RedirectResponse(
          $this->admin->generateUrl('list',$this->admin->getFilterParameters())
        );
    }

    public function batchActionUnpublish(ProxyQueryInterface $selectedModelQuery)
    {
        if (!$this->admin->isGranted('EDIT') || !$this->admin->isGranted('DELETE'))
        {
            throw new AccessDeniedException();
        }

        $selectedModels = $selectedModelQuery->execute();

        if ($this->changePublicationAction($selectedModels, false)) {
            $this->addFlash('sonata_flash_success', 'flash_batch_publish_success');
        } else {
            $this->addFlash('sonata_flash_error', 'flash_batch_publish_error');
        }

        return new RedirectResponse(
          $this->admin->generateUrl('list',$this->admin->getFilterParameters())
        );
    }

    private function changePublicationAction($selectedModels, $published)
    {
        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setPublished($published);
                $modelManager->update($selectedModel);
            }


        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());
            return false;
        }

        return true;
    }
}
