<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   03 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender;

/**
 * used by HtmlRender to help format table tags like <table>, <tr>, <td> etc
 */
class TableElement
{
    /** @var string[] */
    private array $styles = [];

    private array $attributes = [];

    private string $content = '';

    private string $prefix = '';

    private string $suffix = '';

    public function __construct(private string $tag, private bool $showIfEmpty = true)
    {
    }

    public function addStyle(string $style): static
    {
        if (!empty($style)) {
            $this->styles[] = $style;
        }
        return $this;
    }

    /**
     * @param string[] $styles
     * @return static
     */
    public function addStyles(array $styles): static
    {
        $this->styles = array_merge($this->styles, $styles);
        return $this;
    }

    public function addStyleIf(bool $shouldAdd, string $style): static
    {
        if ($shouldAdd) {
            $this->styles[] = $style;
        }
        return $this;
    }

    public function setAttribute(string $id, string $value): static
    {
        assert($id !== 'class', 'use addStyle()');
        $this->attributes[$id] = $value;
        return $this;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function setSuffix(string $suffix): static
    {
        $this->suffix = $suffix;
        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->content);
    }

    public function __toString(): string
    {
        if ($this->isEmpty() && !$this->showIfEmpty) {
            return '';
        }
        $html = $this->prefix;
        $html .= '<' . $this->tag;
        if (!empty($this->styles)) {
            $html .= ' class="' . join(' ', array_unique($this->styles)) . '"';
        }
        foreach ($this->attributes as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '"';
        }
        $html .= '>' . $this->content . '</' . $this->tag . '>';
        $html .= $this->suffix;
        return $html;
    }
}
