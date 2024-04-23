<?php
declare(strict_types=1);

namespace MOC\NotFound\Fusion\Eel\Helper;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service;

/**
 * See: https://github.com/neos/neos-development-collection/issues/3190#issuecomment-730361990
 * Thanks to @kdambekalns
 *
 * The LocaleHelper deals with i18n-related things.
 */
class LocaleHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Service
     */
    protected $i18nService;

    /**
     * Given a node this tries to set the current locale for the Flow i18n service
     * from the content dimension "language", if possible.
     *
     * The input node is returned as is for chaining, to make sure the operation is
     * actually evaluated.
     *
     * @param NodeInterface $node
     * @return NodeInterface
     * @throws \Neos\Flow\I18n\Exception\InvalidLocaleIdentifierException
     */
    public function setCurrentFromNode(NodeInterface $node, string $languageDimensionName): NodeInterface
    {
        $dimensions = $node->getContext()->getDimensions();
        if (array_key_exists($languageDimensionName, $dimensions) && $dimensions[$languageDimensionName] !== []) {
            $currentLocale = new Locale($dimensions[$languageDimensionName][0]);
            $this->i18nService->getConfiguration()->setCurrentLocale($currentLocale);
            $this->i18nService->getConfiguration()->setFallbackRule(['strict' => false, 'order' => array_reverse($dimensions[$languageDimensionName])]);
        }

        return $node;
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}