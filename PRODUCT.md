# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Laravel + Livewire + Tailwind CSS v3 + Vite. Alpine.js via x-data in layouts. Redis for real-time KDS updates via Laravel Echo/Pusher.

## Users

Kasir (cashier), Barista, Manager/Supervisor, Owner/Admin, Gudang (warehouse). Coffee shop staff using a unified POS system across desktop and tablet browsers.

## Product Purpose

Good Coffee POS is a unified point-of-sale system for coffee shops — handling order entry (POS), kitchen display (KDS), inventory management, sales analytics, and reporting in one elegant dashboard.

## Positioning

Purpose-built for coffee shop workflows: from bean to cup tracking, the system auto-deducts stock per recipe (BOM) and routes orders from counter to kitchen in real time.

## Operating Context

Desktop browsers (Chrome, Edge, Safari) primarily, tablet browsers for counter/kitchen use. KDS runs on dedicated screens. POS operates at the counter with menu grid + cart flow. Offline resilience is valued.

## Capabilities and Constraints

- Livewire-driven SPA-like interfaces with wire:navigate and wire:poll for KDS
- Role-based access (Kasir, Barista/Gudang, Manager/Supervisor, Owner/Admin)
- Shift management for POS sessions
- Table management for dine-in orders
- PPN 11% tax, discount support
- Real-time order status updates via Echo/Pusher on KDS
- Print receipt via browser print dialog
- Responsive required but no native mobile app

## Brand Commitments

- Name: Good Coffee
- Primary color: #398263 (forest green)
- Surface: white #FFFFFF on #FAFAFA background
- Fonts: Playfair Display (serif display) + Inter (UI sans)
- Coffee shop aesthetic — warm, crafted, approachable
- Logo mark: "Good Coffee." in Playfair Display

## Evidence on Hand

- Blade templates in resources/views and resources/views/livewire/
- Tailwind config with primary #398263, surface whites, border grays
- CSS in resources/css/app.css with Inter + Playfair Display fonts
- Vite build pipeline with tailwindcss v3
- 14 blade component files, 5 Livewire POS components, KDS display

## Accessibility & Inclusion

WCAG AA color contrast targets. Focus-visible rings on interactive elements. Keyboard-navigable sidebar and menus. Screen-reader friendly with semantic HTML.

## Product Principles

1. Craft-first UI — every surface should feel like a carefully poured espresso: precise, warm, intentional
2. Role-aware surfaces — each user type sees exactly what they need, nothing more
3. Real-time confidence — KDS and POS stay in sync without manual refresh
4. Mobile-tablet ready — counter and kitchen screens work on any browser viewport
5. Calm density — information-rich without overwhelming, breathing room built into every layout
