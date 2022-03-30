<?php
/**
 * themehelper plugin for Craft CMS 3.x
 *
 * Craft CMS plugin containing twig functions and filters.
 *
 * @link      http://www.kasanova.nl/
 * @copyright Copyright (c) 2018 Jurjen Nieuwenhuis
 */

namespace juni\themehelper\twigextensions;

use craft\elements\Entry;
use craft\helpers\Template as TemplateHelper;

use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Jurjen Nieuwenhuis
 * @package   Themehelper
 * @since     1.0.0
 */
class ThemeHelperTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ThemeHelper';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('scrub', [$this, 'scrub']),
            new TwigFilter('inline', [$this, 'inline']),
            new TwigFilter('monthIndex', [$this, 'monthIndex']),
            new TwigFilter('startsWith', [$this, 'startsWith']),
            new TwigFilter('navTitle', [$this, 'navTitle']),
            new TwigFilter('lead', [$this, 'lead']),
            new TwigFilter('typography', [$this, 'typography']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('scrub', [$this, 'scrub']),
            new TwigFunction('inline', [$this, 'inline']),
            new TwigFunction('monthIndex', [$this, 'monthIndex']),
            new TwigFunction('startsWith', [$this, 'startsWith']),
            new TwigFunction('navTitle', [$this, 'navTitle']),
            new TwigFunction('lead', [$this, 'lead']),
            new TwigFunction('typography', [$this, 'typography']),
        ];
    }

    /**
     * Removes empty <p/> tags from the html text. It also removes any non-breaking spaces.
     *
     * @param string $text
     * @return string
     */
    public function scrub($text = null)
    {
        $newText = preg_replace('/<p[^>]*?><\/p>/i', '', $text);
        $newText = preg_replace('/<(p|h[1-6])><br \/><\/(p|h[1-6])>/i', '', $newText);
        $newText = str_replace('&nbsp;', ' ', $newText);

        return TemplateHelper::raw($newText);
    }

    /**
     * Strip <p> tags from rich text field
     *
     * @param string $var
     * @return mixed
     */
    public function inline($var)
    {
        $newVar = preg_replace('/<p[^>]*?>/i', '', $var);
        $newVar = str_replace('</p>', '<br>', $newVar);
        $newVar = preg_replace('/<br>$/', '', $newVar);

        return TemplateHelper::raw($newVar);
    }

    public function monthIndex($monthName)
    {
        return date('m', strtotime($monthName));
    }

    public function startsWith($string, $needle)
    {
        return strpos($string, $needle) === 0;
    }

    public function navTitle(Entry $entry)
    {
        $title = $entry->navigationTitle;

        if (empty($title))
        {
            $title = $entry->title;
        }

        return TemplateHelper::raw($title);
    }


    /**
     * Add the class 'lead' to the paragraph elements
     *
     * @param string $var
     * @return mixed
     */
    public function lead($var)
    {
        return TemplateHelper::raw($this->addCssClass($var, 'lead'));
    }

    private function addCssClass($html, $class, $element = 'p')
    {
        $replace = sprintf('<%s class="%s">', $element, $class);
        $pattern = sprintf('/<%s[^>]*?>/i', $element);

        $out = preg_replace($pattern, $replace, $html);

        return $out;
    }

    public function typography($var)
    {
        $var = $this->addCssClass($var, 'unordered-list', 'ul');
        $var = $this->addCssClass($var, 'ordered-list', 'ol');
        $var = $this->addCssClass($var, 'table table-striped table-hover', 'table');

        return TemplateHelper::raw($var);
    }
}
