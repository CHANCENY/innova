<?php

namespace Innova\modules;

class URIPatternMatcher {
    private $pattern;

    public function __construct($pattern) {
        $this->pattern = $this->generateRegexPattern($pattern);
    }

    public function match($uri) {
        if (preg_match($this->pattern, $uri, $matches)) {
            return array_slice($matches, 1); // Skip the full match
        }
        return false;
    }

    public function getPattern() {
        return $this->pattern;
    }

    private function generateRegexPattern($pattern) {
        $pattern = preg_quote($pattern, '#'); // Use # as delimiter
        $pattern = preg_replace('#\\\{[a-zA-Z0-9_]+\\\}#', '([^/]+)', $pattern); // Escape { and }
        return '#^' . $pattern . '$#';
    }
}

// Example usage:
$uriPattern = new URIPatternMatcher("/posts/ads/{parameter}/{parameter2}/{parameter3}");
$uri = "/posts/ads/how-make-a-blog/parameter2-value/parameter3-value";
$params = $uriPattern->match($uri);

