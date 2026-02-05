<?php

namespace App\Services;

class PosterGenerator
{
    public static function generateConfig($job)
    {
        // Use job ID or unique string to seed the random generator for consistency
        $seed = crc32($job['id'] ?? $job['job_id'] ?? uniqid());
        mt_srand($seed);

        // 1. Color Palettes (Backgrounds & Text)
        $palettes = [
            ['bg' => 'bg-gradient-to-br from-purple-600 to-blue-600', 'text' => 'text-white', 'accent' => 'bg-white/20'],
            ['bg' => 'bg-gradient-to-tr from-emerald-500 to-teal-900', 'text' => 'text-white', 'accent' => 'bg-emerald-800/30'],
            ['bg' => 'bg-gradient-to-bl from-rose-500 to-orange-400', 'text' => 'text-white', 'accent' => 'bg-white/20'],
            ['bg' => 'bg-gray-900', 'text' => 'text-white', 'accent' => 'bg-gray-800'],
            ['bg' => 'bg-blue-900', 'text' => 'text-blue-50', 'accent' => 'bg-blue-800'],
            ['bg' => 'bg-white', 'text' => 'text-gray-900', 'accent' => 'bg-gray-100'],
            ['bg' => 'bg-[#F4D03F]', 'text' => 'text-black', 'accent' => 'bg-black/10'], // Yellow
            ['bg' => 'bg-[#E74C3C]', 'text' => 'text-white', 'accent' => 'bg-white/20'], // Red
            ['bg' => 'bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900', 'text' => 'text-gray-100', 'accent' => 'bg-purple-500/20'],
            // TikTok Modern Dark
            ['bg' => 'bg-gradient-to-b from-teal-900 via-gray-900 to-rose-900', 'text' => 'text-white', 'accent' => 'bg-gray-800'],
            // Pastel Palettes
            ['bg' => 'bg-[#FFDEE9] bg-gradient-to-b from-[#FFDEE9] to-[#B5FFFC]', 'text' => 'text-slate-800', 'accent' => 'bg-white/50'], // Pink/Blue Pastel
            ['bg' => 'bg-[#D9AFD9] bg-gradient-to-tr from-[#D9AFD9] to-[#97D9E1]', 'text' => 'text-slate-900', 'accent' => 'bg-white/40'], // Purple/Blue Pastel
            ['bg' => 'bg-[#FDFBF7]', 'text' => 'text-stone-800', 'accent' => 'bg-stone-200'], // Minimalist Cream
            // Contrast Palettes
            ['bg' => 'bg-black', 'text' => 'text-[#CCFF00]', 'accent' => 'bg-[#CCFF00]/20'], // Cyber Acid
            ['bg' => 'bg-[#FF0050]', 'text' => 'text-white', 'accent' => 'bg-black/20'], // TikTok Red
            ['bg' => 'bg-[#0000FF]', 'text' => 'text-white', 'accent' => 'bg-white/20'], // Pure Blue
            ['bg' => 'bg-orange-500', 'text' => 'text-black', 'accent' => 'bg-black/10'], // Orange/Black
        ];
        $palette = $palettes[mt_rand(0, count($palettes) - 1)];

        // 2. Patterns (Overlay styles)
        $patterns = [
            'none',
            'radial-dots', // radial-gradient
            'grid-lines', // repeating-linear-gradient
            'noise',      // noisy texture
            'circles',     // large circles
            'diagonal-stripes',
            'zigzag',
            'polka-pop',
            'waves',
            'isometric',
            'checkerboard'
        ];
        $pattern = $patterns[mt_rand(0, count($patterns) - 1)];

        // 3. Fonts
        $fonts = [
            'font-sans', // Inter (Default)
            'font-serif', // Playfair Display
            'font-mono', // Space Mono
            'font-oswald', // Oswald
            'font-comic', // Comic Sans (Meme)
            'font-retro', // Retro/Pixel
            'font-display-heavy' // Impact-like
        ];
        $font = $fonts[mt_rand(0, count($fonts) - 1)];

        // 4. Layouts
        $layouts = [
            'centered',      // Everything center aligned nicely
            'left-aligned',  // Classic left align
            'card-center',   // Floating card in center
            'split-vertical',// Logo top, content bottom massive
            'minimalist',    // Very small text, lots of whitespace
            'tiktok-modern', // New Glitch/Neon layout
            'modern-split',  // High contrast split screen
            'cyber-grid',    // Terminal aesthetic
            'bold-typography', // Massive text focus
            'neobrutalism',   // Brutalist borders, high contrast
            'retro-synth',    // 80s style
            'glass-modern',   // Glassmorphism
            'meme-design'     // Chaotic "Design is my passion"
        ];
        $layout = $layouts[mt_rand(0, count($layouts) - 1)];

        // 5. Button Styles
        $buttonStyles = [
            'solid-pill',      // Classic rounded full color
            'outline-neon',    // Transparent with glowing border
            'glass',           // Backdrop blur white/glass
            'brutalist',       // Sharp corners, heavy shadow
            'gradient-shine',   // Gradient background
            'retro-windows',    // Windows 95 style
            'pixel-art'         // Pixelated borders
        ];
        $buttonStyle = $buttonStyles[mt_rand(0, count($buttonStyles) - 1)];

        // 6. Decorative Elements
        $decorations = [
            'none',
            'watermark-logo', // Huge logo in background
            'shapes-corner',  // Abstract blobs
            'line-separator', // Lines between elements
            'border-frame'    // Border around content
        ];
        $decoration = $decorations[mt_rand(0, count($decorations) - 1)];

        return [
            'palette' => $palette,
            'pattern' => $pattern,
            'font' => $font,
            'layout' => $layout,
            'decoration' => $decoration,
            'button_style' => $buttonStyle
        ];
    }
}
