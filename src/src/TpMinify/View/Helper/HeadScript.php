<?php
/**
 * TpMinify - third-party module for the Zend Framework 2
 *
 * @category Module
 * @package  TpMinify
 * @author   Kanstantsin A Kamkou (2ka.by)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/kkamkou/tp-minify/
 */

namespace TpMinify\View\Helper;
use Laminas\View\Helper\HeadScript as HeadScriptOriginal;
use Minify;

/**
 * Class HeadScript
 * @package TpMinify\View\Helper
 * @see ServiceLocatorAwareInterface
 */
class HeadScript extends HeadScriptOriginal
{

	/**
	 * @var array $config	Configuration Array from Laminas
	 */
	protected $config;

	public function __construct(array $config){
		$this->config = $config;
	}

    /**
     * Create script HTML
     *
     * @param  mixed  $item        Item to convert
     * @param  string $indent      String to add before the item
     * @param  string $escapeStart Starting sequence
     * @param  string $escapeEnd   Ending sequence
     * @return string
     */
    public function itemToString($item, $indent, $escapeStart, $escapeEnd)
    {
        if (!empty($item->source)) {
            $config = $this->getConfig();
            $config = $config['TpMinify']['helpers']['headScript'];
            if ($config['enabled']) {
                $result = Minify::serve(
                    'Files',
                    array_merge(
                        $config,
                        array(
                            'quiet' => true,
                            'encodeOutput' => false,
                            'files' => new \Minify_Source(
                                array(
                                    'contentType' => Minify::TYPE_JS,
                                    'content' => $item->source,
                                    'id' => __CLASS__ . hash('crc32', $item->source)
                                )
                            )
                        )
                    )
                );

                if ($result['success']) {
                    $item->source = $result['content'];
                }
            }
        }

        return parent::itemToString($item, $indent, $escapeStart, $escapeEnd);
    }

	/**
	 * @return array
	 */
    protected function getConfig() : array{
    	return $this->config;
	}
}
