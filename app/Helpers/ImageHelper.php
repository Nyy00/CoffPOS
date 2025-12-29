<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the correct URL for an image stored in storage
     * 
     * @param string|null $imagePath
     * @param string $fallback
     * @return string
     */
    public static function getImageUrl($imagePath, $fallback = null)
    {
        if (empty($imagePath)) {
            return $fallback ? asset($fallback) : asset('images/placeholder.png');
        }

        // If path already starts with http/https, return as is
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        // Remove 'products/' prefix if it exists (for backward compatibility)
        $cleanPath = str_replace('products/', '', $imagePath);
        
        // Try storage first (for new uploads)
        $storagePath = 'products/' . $cleanPath;
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::url($storagePath);
        }

        // Fallback to asset (for existing files in public/images)
        $assetPath = 'images/products/' . $cleanPath;
        if (file_exists(public_path($assetPath))) {
            return asset($assetPath);
        }

        // Return fallback or placeholder
        return $fallback ? asset($fallback) : asset('images/placeholder.png');
    }

    /**
     * Get product image URL with automatic fallback
     * 
     * @param string|null $imagePath
     * @param string $productName
     * @return string
     */
    public static function getProductImageUrl($imagePath, $productName = '')
    {
        // Determine fallback based on product name
        $fallback = 'images/products/placeholder-product.png';
        
        if (!empty($productName)) {
            $productName = strtolower($productName);
            if (str_contains($productName, 'coffee') || str_contains($productName, 'espresso') || str_contains($productName, 'latte')) {
                $fallback = 'images/products/coffee-default.jpg';
            } elseif (str_contains($productName, 'tea')) {
                $fallback = 'images/products/green-tea.jpg';
            }
        }

        return self::getImageUrl($imagePath, $fallback);
    }

    /**
     * Get category image URL
     * 
     * @param string|null $imagePath
     * @return string
     */
    public static function getCategoryImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return asset('images/categories/placeholder-category.png');
        }

        // Remove 'categories/' prefix if it exists
        $cleanPath = str_replace('categories/', '', $imagePath);
        
        // Try storage first
        $storagePath = 'categories/' . $cleanPath;
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::url($storagePath);
        }

        // Fallback to asset
        return asset('images/categories/' . $cleanPath);
    }

    /**
     * Get user avatar URL
     * 
     * @param string|null $imagePath
     * @return string
     */
    public static function getAvatarUrl($imagePath)
    {
        if (empty($imagePath)) {
            return asset('images/avatars/default-avatar.png');
        }

        // Remove 'avatars/' prefix if it exists
        $cleanPath = str_replace('avatars/', '', $imagePath);
        
        // Try storage first
        $storagePath = 'avatars/' . $cleanPath;
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::url($storagePath);
        }

        // Fallback to asset
        return asset('images/avatars/' . $cleanPath);
    }

    /**
     * Get receipt image URL
     * 
     * @param string|null $imagePath
     * @return string|null
     */
    public static function getReceiptUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        // If path already starts with http/https, return as is
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        // Remove 'receipts/' prefix if it exists (for backward compatibility)
        $cleanPath = str_replace('receipts/', '', $imagePath);
        
        // Try storage first (for new uploads)
        $storagePath = 'receipts/' . $cleanPath;
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::url($storagePath);
        }

        // Try without receipts folder (direct path)
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::url($cleanPath);
        }

        // Try original path as is
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::url($imagePath);
        }

        // Fallback to asset (for existing files in public/images)
        $assetPath = 'images/receipts/' . $cleanPath;
        if (file_exists(public_path($assetPath))) {
            return asset($assetPath);
        }

        // Try direct asset path
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        // Return null if not found (don't show placeholder for receipts)
        return null;
    }
}