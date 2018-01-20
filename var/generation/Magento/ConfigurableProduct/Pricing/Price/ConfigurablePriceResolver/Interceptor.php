<?php
namespace Magento\ConfigurableProduct\Pricing\Price\ConfigurablePriceResolver;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Pricing\Price\ConfigurablePriceResolver
 */
class Interceptor extends \Magento\ConfigurableProduct\Pricing\Price\ConfigurablePriceResolver implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\ConfigurableProduct\Pricing\Price\PriceResolverInterface $priceResolver, \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProviderInterface $lowestPriceOptionsProvider = null)
    {
        $this->___init();
        parent::__construct($priceResolver, $configurable, $priceCurrency, $lowestPriceOptionsProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePrice(\Magento\Framework\Pricing\SaleableInterface $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resolvePrice');
        if (!$pluginInfo) {
            return parent::resolvePrice($product);
        } else {
            return $this->___callPlugins('resolvePrice', func_get_args(), $pluginInfo);
        }
    }
}
