<?php

namespace Jb\Bundle\SimpleCrudBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList;

/**
 * CrudMetadataParamConverter
 *
 * @author jobou
 */
class CrudMetadataParamConverter implements ParamConverterInterface
{
    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList
     */
    protected $metadataList;

    /**
     * Constructor
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList $metadataList
     */
    public function __construct(CrudMetadataList $metadataList)
    {
        $this->metadataList = $metadataList;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();

        if (!$request->attributes->has('entity')) {
            throw new \LogicException('The route uses a CrudMetadata but does not have an entity parameter');
        }

        $metadata = $this->metadataList->getMetadata($request->attributes->get('entity'));

        $request->attributes->set($name, $metadata);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return 'Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata' === $configuration->getClass();
    }
}
