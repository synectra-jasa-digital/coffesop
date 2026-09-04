---
name: Coffee Shop UI System
description: Comprehensive design rules for Good Coffee, covering both the premium Landing Page (Persuade) and the robust internal system (Operate: POS, Admin, KDS).
---

# Good Coffee Design System

## Overview
This design system governs two distinct but unified modes:
1. **Persuade (Customer-Facing/Landing Page):** A premium, authentic, and modern editorial UI. Highlights quality, organic beans, and elegance.
2. **Operate (Internal System - POS, Admin, KDS, Shift):** A high-utility, fast, and legible interface for staff. Focused on task completion, scannability, and clear visual hierarchy under pressure.

---

## 1. Persuade: The Premium Experience (Landing Page)
**Goal:** Earn attention, action, and convey high-end quality.

### Colors (Persuade)
- **Primary:** `#398263` (Teal/Green). Used for CTAs and breaking up sections.
- **Primary Hover:** `#2C6B4F`
- **Background:** `#FAFAFA`
- **Surface:** `#FFFFFF`
- **Surface Dark:** `#0A0A0A`
- **Text Primary:** `#1A1A1A`
- **Text Secondary:** `#6B7280`
- **Text Inverse:** `#FFFFFF`
- **Border:** `#E5E7EB`

### Typography (Persuade)
- **Headings (Playfair Display, serif):**
  - H1: 4rem, 700, 1.1 LH, -0.02em tracking
  - H2: 2.5rem, 700, 1.2 LH
  - H3: 1.75rem, 600, 1.3 LH
- **Body & Labels (Inter, sans-serif):**
  - Body: 1rem, 400, 1.6 LH
  - Label: 0.875rem, 500

### Spacing & Elevation (Persuade)
- **Generous Whitespace:** 80px - 120px between sections.
- **Elevation:** Flat design. NO drop shadows. Depth achieved through solid color blocking.
- **Shapes:** Sharp/Minimal (2px radius). Avoid pillowy/bubbly modern SaaS looks.

---

## 2. Operate: The Robust Staff System (Admin, POS, KDS)
**Goal:** High density, rapid operation, flawless scannability.

### Colors (Operate)
- Uses the same core palette but leans heavily on utilitarian grays.
- **Action/Success:** `#398263` (Matches brand).
- **Warning/Alert:** `#D97706` (Amber 600 - e.g., Low Stock).
- **Danger/Destructive:** `#DC2626` (Red 600 - e.g., Cancel Order, Delete).
- **Background (App):** `#F3F4F6` (Gray 100 - to make white cards pop slightly).
- **Surface (Cards/Modals):** `#FFFFFF`
- **Borders (Dividers/Tables):** `#E5E7EB`

### Typography (Operate)
- **Sans-serif only (Inter) for maximum legibility on busy screens.** No Playfair Display in the internal app unless it's a specific brand header.
- **Data/Tables:** Tabular figures if possible. Small text (0.875rem) for dense tables.
- **Hierarchy:** Clear distinction between data labels (gray, small, uppercase) and data values (black, medium/bold).

### Layout, Spacing & Elevation (Operate)
- **Density:** Higher density. Spacing uses smaller increments (4px, 8px, 16px, 24px) for menus, tables, and grids.
- **Cards/Containers:** Simple 1px borders, subtle or no shadows (max shadow-sm) to distinguish active areas.
- **Sidebar/Nav:** Clean, collapsible or fixed sidebar with clear active states.
- **KDS (Kitchen Display System):** Must be readable from a distance. High contrast. Large card views for orders. Color-coded by status (New, Preparing, Ready).

---

## Universal Rules to Never Break
1. **Never** use gradient backgrounds. Stick to solid and flat colors.
2. **Never** use heavy, blurry drop shadows on marketing pages. Keep elements crisp.
3. **Always** ensure high contrast for text (especially in POS and KDS where glare or distance is a factor).
4. **Never** misalign numbers in tables (always right-align currency/numbers).
5. **Form Controls:** Inputs in the Admin/POS must have clear, visible boundaries (1px solid border). No floating/underline-only inputs.