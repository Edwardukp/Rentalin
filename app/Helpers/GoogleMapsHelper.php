<?php

namespace App\Helpers;

class GoogleMapsHelper
{
    /**
     * Extract coordinates from Google Maps URL
     * Supports various Google Maps URL formats
     */
    public static function extractCoordinatesFromUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // Pattern 1: @lat,lng,zoom format
        if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            return [
                'latitude' => (float) $matches[1],
                'longitude' => (float) $matches[2]
            ];
        }

        // Pattern 2: ll=lat,lng format
        if (preg_match('/ll=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            return [
                'latitude' => (float) $matches[1],
                'longitude' => (float) $matches[2]
            ];
        }

        // Pattern 3: q=lat,lng format
        if (preg_match('/q=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            return [
                'latitude' => (float) $matches[1],
                'longitude' => (float) $matches[2]
            ];
        }

        // Pattern 4: place_id or address-based URLs - return null as we can't extract coordinates
        return null;
    }

    /**
     * Generate Google Maps embed URL for iframe
     */
    public static function generateEmbedUrl($googleMapsUrl, $width = 600, $height = 450)
    {
        if (empty($googleMapsUrl)) {
            return null;
        }

        $apiKey = config('services.google_maps.api_key', '');

        // Check if API key is valid (not empty and not the placeholder)
        if (empty($apiKey) || $apiKey === 'your_google_maps_api_key_here') {
            // Try alternative embed methods without API key
            return self::generateAlternativeEmbedUrl($googleMapsUrl, $width, $height);
        }

        // Extract coordinates from the URL
        $coordinates = self::extractCoordinatesFromUrl($googleMapsUrl);

        if ($coordinates) {
            // Use coordinates for embed with API key
            $lat = $coordinates['latitude'];
            $lng = $coordinates['longitude'];
            return "https://www.google.com/maps/embed/v1/view?key={$apiKey}&center={$lat},{$lng}&zoom=15";
        }

        // Fallback to alternative methods
        return self::generateAlternativeEmbedUrl($googleMapsUrl, $width, $height);
    }

    /**
     * Generate alternative embed URL without API key
     */
    public static function generateAlternativeEmbedUrl($googleMapsUrl, $width = 600, $height = 450)
    {
        if (empty($googleMapsUrl)) {
            return null;
        }

        // Extract coordinates from the URL
        $coordinates = self::extractCoordinatesFromUrl($googleMapsUrl);

        if ($coordinates) {
            $lat = $coordinates['latitude'];
            $lng = $coordinates['longitude'];

            // Method 1: OpenStreetMap embed (free, no API key required)
            return "https://www.openstreetmap.org/export/embed.html?bbox=" .
                   ($lng - 0.01) . "," . ($lat - 0.01) . "," .
                   ($lng + 0.01) . "," . ($lat + 0.01) .
                   "&layer=mapnik&marker={$lat},{$lng}";
        }

        // Method 2: Try to convert Google Maps URL to embeddable format
        // Some Google Maps URLs can be embedded without API key
        if (strpos($googleMapsUrl, 'google.com/maps') !== false) {
            // Convert to embed format
            $embedUrl = str_replace('/maps?', '/maps/embed?', $googleMapsUrl);
            $embedUrl = str_replace('/maps/', '/maps/embed/', $embedUrl);

            // Add pb parameter for better embedding (this sometimes works without API key)
            if (strpos($embedUrl, 'embed') !== false && strpos($embedUrl, 'pb=') === false) {
                return $embedUrl;
            }
        }

        return null;
    }

    /**
     * Generate Leaflet map HTML (completely free, no API key required)
     */
    public static function generateLeafletMapHtml($googleMapsUrl, $width = 600, $height = 450)
    {
        $coordinates = self::extractCoordinatesFromUrl($googleMapsUrl);

        if (!$coordinates) {
            return null;
        }

        $lat = $coordinates['latitude'];
        $lng = $coordinates['longitude'];
        $mapId = 'map_' . uniqid();

        return "
        <div id='{$mapId}' style='height: {$height}px; width: 100%; border-radius: 8px;'></div>
        <script>
            if (typeof L !== 'undefined') {
                var map_{$mapId} = L.map('{$mapId}').setView([{$lat}, {$lng}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map_{$mapId});
                L.marker([{$lat}, {$lng}]).addTo(map_{$mapId});
            } else {
                document.getElementById('{$mapId}').innerHTML = '<div style=\"display: flex; align-items: center; justify-content: center; height: 100%; background: #f3f4f6; border-radius: 8px;\"><span>📍 Map location available - click \"Open in Google Maps\" to view</span></div>';
            }
        </script>";
    }

    /**
     * Generate a clean Google Maps link for opening in new tab
     */
    public static function generateCleanMapUrl($googleMapsUrl)
    {
        if (empty($googleMapsUrl)) {
            return null;
        }

        // Extract coordinates from the URL
        $coordinates = self::extractCoordinatesFromUrl($googleMapsUrl);
        
        if ($coordinates) {
            $lat = $coordinates['latitude'];
            $lng = $coordinates['longitude'];
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }

        // Return the original URL if we can't extract coordinates
        return $googleMapsUrl;
    }

    /**
     * Validate if a URL is a valid Google Maps URL
     */
    public static function isValidGoogleMapsUrl($url)
    {
        if (empty($url)) {
            return false;
        }

        $parsedUrl = parse_url($url);
        if (!$parsedUrl || !isset($parsedUrl['host'])) {
            return false;
        }

        $host = strtolower($parsedUrl['host']);
        $path = isset($parsedUrl['path']) ? strtolower($parsedUrl['path']) : '';

        // Check if it's a Google Maps domain and path
        $validHosts = [
            'maps.google.com',
            'www.google.com',
            'google.com',
            'maps.app.goo.gl',
            'goo.gl'
        ];

        $isValidHost = false;
        foreach ($validHosts as $validHost) {
            if ($host === $validHost || str_ends_with($host, '.' . $validHost)) {
                $isValidHost = true;
                break;
            }
        }

        if (!$isValidHost) {
            return false;
        }

        // For google.com and www.google.com, check if path starts with /maps
        if (in_array($host, ['www.google.com', 'google.com'])) {
            return str_starts_with($path, '/maps');
        }

        // For other Google Maps domains, it's valid
        return true;
    }

    /**
     * Get a thumbnail image URL for the location
     */
    public static function getStaticMapThumbnail($googleMapsUrl, $width = 400, $height = 300, $zoom = 15)
    {
        $coordinates = self::extractCoordinatesFromUrl($googleMapsUrl);

        if (!$coordinates) {
            return null;
        }

        $lat = $coordinates['latitude'];
        $lng = $coordinates['longitude'];
        $apiKey = config('services.google_maps.api_key', '');

        // Check if API key is valid (not empty and not the placeholder)
        if (empty($apiKey) || $apiKey === 'your_google_maps_api_key_here') {
            return null;
        }

        return "https://maps.googleapis.com/maps/api/staticmap?center={$lat},{$lng}&zoom={$zoom}&size={$width}x{$height}&maptype=roadmap&markers=color:red%7C{$lat},{$lng}&key={$apiKey}";
    }
}
