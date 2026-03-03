# CK Langkawi: Gutenberg Pages Implementation Guide

## Overview

This guide explains how to create the About Us, Contact Us, and FAQs pages using **Gutenberg Block Editor** in WordPress admin. The block patterns have been registered and are ready to use.

---

## Prerequisites

1. **WordPress admin access** - You need admin credentials
2. **Contact form plugin** - Install one of:
   - **WPForms** (recommended - free version available)
   - **Contact Form 7** (popular free option)
   - **Ninja Forms** (free version)

---

## Step 1: Verify Block Patterns are Registered

1. Go to **WordPress Admin** → **Pages** → **Add New**
2. Click the **+ (Block Inserter)** button
3. Click the **Patterns** tab
4. Look for **CKL** categories:
   - CKL Hero Sections
   - CKL Sections
   - CKL About Page
   - CKL Contact Page
   - CKL FAQ Page
   - CKL Call to Action

✅ If you see these categories, patterns are registered correctly!

---

## Step 2: Create About Us Page

### Navigation:
**Pages → Add New**

### Page Title:
```
About Us
```

### Permalink:
```
/about
```

### Block Structure (in order):

#### 1. Hero Section
- Click **+ Add Block**
- Go to **Patterns** tab
- Search for "Page Hero - About"
- Click to insert

#### 2. Welcome Section
- Add a **Heading** block (H2)
  - Text: "Welcome to CK Langkawi Trip"
  - Alignment: Center
  - Class: `text-3xl font-bold mb-6`

- Add a **Spacer** block (height: 32px)

- Add a **Group** block
  - Background: White
  - Padding: 32px
  - Border radius: 8px
  - Inside the group, add **Columns** (50/50)

  **Left Column:**
  - Heading (H3): "Our Story"
  - Paragraph: "CK LANGKAWI TRIP (002800247-T) is a premier vehicle rental service provider based in the beautiful island of Langkawi, Kedah Darul Aman. We specialize in providing reliable, affordable, and well-maintained vehicles to help you explore the wonders of Langkawi at your own pace."
  - Paragraph: "Whether you're visiting for a holiday, business trip, or special event, our diverse fleet of vehicles - from motorcycles and compact cars to luxury MPVs and buses - ensures you'll find the perfect ride for your needs."
  - Paragraph: "We take pride in our commitment to customer satisfaction, transparent pricing, and well-maintained vehicles. Our team is dedicated to making your Langkawi experience memorable and hassle-free."

  **Right Column:**
  - Go to **Patterns** → Search "Section - Company Info"
  - Insert the pattern

#### 3. Mission & Vision Section
- Go to **Patterns** → Search "Section - Mission Vision"
- Click to insert

#### 4. Core Values Section
- Add a **Heading** (H2): "Our Core Values"
- Alignment: Center
- Add a **Spacer** (height: 48px)

- Go to **Patterns** → Search "Section - Core Values"
- Click to insert

#### 5. Services Section
- Add a **Heading** (H2): "Our Services"
- Alignment: Center
- Add a **Paragraph**: "We offer a comprehensive range of vehicle rental options to suit every need and budget"
- Alignment: Center
- Add a **Spacer** (height: 48px)

- Go to **Patterns** → Search "Section - Services"
- Click to insert

#### 6. Why Choose Us Section
- Add a **Group** block
- Background: White
- Padding: 64px top/bottom
- Alignment: Center

- Add **Heading** (H2): "Why Choose CK Langkawi?"
- Add a **Spacer** (height: 48px)

- Add **Columns** (3 columns)

  **Column 1:**
  - Paragraph (emoji): "💰"
  - Heading (H3): "Best Prices"
  - Paragraph: "Competitive rates with no hidden fees"

  **Column 2:**
  - Paragraph (emoji): "🔧"
  - Heading (H3): "Well Maintained"
  - Paragraph: "All vehicles regularly serviced and insured"

  **Column 3:**
  - Paragraph (emoji): "🕐"
  - Heading (H3): "24/7 Support"
  - Paragraph: "We're here whenever you need us"

#### 7. CTA Section
- Go to **Patterns** → Search "Section - CTA"
- Click to insert
- Edit the heading if needed

### Page Settings:
- **Template**: Default (no template assigned)
- **Page Attributes**: Parent: None
- **Featured Image**: Optional (add if desired)

### Publish:
1. Click **Preview** to check the page
2. Click **Publish**
3. Add to menu (Appearance → Menus)

---

## Step 3: Create Contact Us Page

### Navigation:
**Pages → Add New**

### Page Title:
```
Contact Us
```

### Permalink:
```
/contact
```

### Block Structure (in order):

#### 1. Hero Section
- Go to **Patterns** → Search "Page Hero - Contact"
- Click to insert

#### 2. Contact Content Section
- Add a **Group** block
- Padding: 64px top/bottom, 24px left/right
- Max width: 1200px
- Margin: Auto (center)

- Add **Columns** (50/50)

  **Left Column - Contact Info:**
  - Go to **Patterns** → Search "Section - Contact Info"
  - Insert the pattern

  **Right Column - Contact Form:**
  - Add a **Group** block
  - Background: White
  - Padding: 32px
  - Border radius: 8px
  - Shadow: Medium

  - Add **Heading** (H2): "Send Us a Message"
  - Add your form block:
    - **WPForms**: Click **+** → Search "WPForms" → Select your form
    - **Contact Form 7**: Add **Shortcode** block → Enter `[contact-form-7 id="X"]`
    - **Or**: Use custom HTML form (see below)

  - Add **Paragraph** at bottom: "We'll get back to you within 24 hours"
  - Alignment: Center
  - Font size: Small

#### 3. Map Section (Optional)
- Add a **HTML** block
- Paste Google Maps embed code:
```html
<div style="width: 100%; height: 400px;">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.123456789!2d99.8!3d6.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTgnMDAuMCJOIDk5wrA0OCcwMC4wIkU!5e0!3m2!1sen!2smy!4v1234567890"
        width="100%"
        height="400"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</div>
```

### Custom Contact Form (if not using plugin):

Add this in an **HTML** block:

```html
<form id="contact-form" method="post" action="">
    <div style="margin-bottom: 1rem;">
        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
            Name <span style="color: #dc2626;">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               required
               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
            Email <span style="color: #dc2626;">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               required
               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
            Phone Number
        </label>
        <input type="tel"
               id="phone"
               name="phone"
               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
            Message <span style="color: #dc2626;">*</span>
        </label>
        <textarea id="message"
                  name="message"
                  rows="6"
                  required
                  style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; resize: none;"></textarea>
    </div>

    <button type="submit"
            style="width: 100%; background-color: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
        Send Message
    </button>

    <?php wp_nonce_field('ckl_contact_form', 'ckl_contact_nonce'); ?>
</form>
```

### Page Settings:
- **Template**: Default (no template assigned)

### Publish:
1. Click **Preview** to check the page
2. Click **Publish**
3. Add to menu

---

## Step 4: Create FAQs Page

### Navigation:
**Pages → Add New**

### Page Title:
```
FAQs
```

### Permalink:
```
/faq
```

### Block Structure (in order):

#### 1. Hero Section
- Go to **Patterns** → Search "Page Hero - FAQ"
- Click to insert

#### 2. Search Box (Optional)
- Add a **Group** block
- Background: White
- Padding: 32px top/bottom
- Border bottom: 1px solid #e5e7eb
- Max width: 672px
- Margin: Auto (center)

- Add a **Search** block
  - Placeholder: "Search for answers..."
  - Button text: "Search"
  - Max width: 672px

#### 3. FAQ Categories

Create 4 categories using **Details** blocks (native WordPress accordion):

**Category 1: General Booking Questions**
- Add a **Group** block
  - Bottom margin: 48px

- Add a **Heading** (H2) with icon:
  - Text: "📋 General Booking Questions"
  - Color: Blue (#2563eb)

- Add multiple **Details** blocks (one per FAQ):

  **FAQ 1:**
  ```
  Summary: How do I book a vehicle?
  Content: Booking a vehicle is easy! Simply browse our available vehicles, select your preferred dates, choose the vehicle that suits your needs, and complete the booking process online. You'll receive a confirmation email with all the details.
  ```

  **FAQ 2:**
  ```
  Summary: What documents do I need to rent a vehicle?
  Content: You'll need a valid driving license (international license if not in English), passport or ID card, and a credit/debit card for the security deposit. The minimum age requirement is 21 years old for cars and 18 years old for motorcycles.
  ```

  **FAQ 3:**
  ```
  Summary: Can I cancel my booking?
  Content: Yes, you can cancel your booking. Free cancellation is available up to 48 hours before your pickup date. Cancellations made within 48 hours may incur a cancellation fee. Please refer to our Terms & Conditions for detailed information.
  ```

  **FAQ 4:**
  ```
  Summary: How early should I book?
  Content: We recommend booking as early as possible, especially during peak seasons (December-January, school holidays, and public holidays) to ensure availability and get the best rates.
  ```

  **FAQ 5:**
  ```
  Summary: Do you offer airport pickup?
  Content: Yes! We offer airport pickup and drop-off services at Langkawi International Airport. Please indicate your flight details when booking, and our team will be ready to welcome you.
  ```

**Category 2: Rental Terms & Conditions**
- Add a **Group** block
  - Bottom margin: 48px

- Add a **Heading** (H2) with icon:
  - Text: "📝 Rental Terms & Conditions"
  - Color: Blue (#2563eb)

- Add 5 **Details** blocks with FAQs about age requirements, deposits, fuel policy, mileage limits, and late returns.

**Category 3: Payment Information**
- Add a **Group** block
  - Bottom margin: 48px

- Add a **Heading** (H2) with icon:
  - Text: "💳 Payment Information"
  - Color: Blue (#2563eb)

- Add 4 **Details** blocks with FAQs about payment methods, upfront payment, refund policy, and hidden fees.

**Category 4: Vehicle Information**
- Add a **Group** block
  - Bottom margin: 48px

- Add a **Heading** (H2) with icon:
  - Text: "🚗 Vehicle Information"
  - Color: Blue (#2563eb)

- Add 5 **Details** blocks with FAQs about insurance, inter-state travel, pets, additional drivers, and breakdowns.

#### 4. CTA Section
- Go to **Patterns** → Search "Section - CTA"
- Click to insert
- Edit heading: "Still Have Questions?"
- Edit subheading: "Our friendly team is here to help! Contact us and we'll get back to you as soon as possible."
- Update buttons: "Contact Us" and "Email Us" (mailto:contact@cklangkawi.com)

### Page Settings:
- **Template**: Default (no template assigned)

### Publish:
1. Click **Preview** to check the page
2. Click **Publish**
3. Add to menu

---

## Step 5: Add Pages to Navigation Menu

### Navigation:
**Appearance → Menus**

1. Select your main menu (usually "Primary Menu")
2. Add the new pages:
   - About Us
   - Contact Us
   - FAQs
3. Reorder if needed
4. Click **Save Menu**

---

## Step 6: Verify Pages Work

### Checklist:

**About Us Page:**
- [ ] Hero section displays with blue gradient background
- [ ] Company info box shows all details
- [ ] Mission & Vision sections side-by-side
- [ ] Core values grid (4 columns on desktop, 2 on tablet, 1 on mobile)
- [ ] Services section shows all 6 services
- [ ] CTA buttons link to correct pages
- [ ] Design matches original 99%

**Contact Us Page:**
- [ ] Hero section displays
- [ ] Contact info shows address, email, operating hours
- [ ] Contact form displays and can be filled out
- [ ] Form submission sends email (test it!)
- [ ] Map displays (if added)
- [ ] Mobile responsive

**FAQs Page:**
- [ ] Hero section displays
- [ ] Search box works (if added)
- [ ] All 4 categories visible
- [ ] Each FAQ accordion expands/collapses
- [ ] Only one FAQ open at a time (better UX)
- [ ] CTA section at bottom
- [ ] Mobile responsive

---

## Troubleshooting

### Patterns not appearing:
1. Clear WordPress cache
2. Deactivate/reactivate theme
3. Check functions.php for errors
4. Verify pattern files exist in `/patterns/` folder

### Styles not applying:
1. Clear browser cache
2. Clear WordPress cache
3. Check style.css is loading
4. Inspect element for CSS conflicts

### Form not submitting:
1. Check form plugin is activated
2. Verify form settings
3. Check WordPress email logs
4. Test with different email address

### Layout broken on mobile:
1. Check responsive CSS in style.css
2. Test on actual mobile device
3. Use browser DevTools mobile emulation
4. Check column widths and padding

---

## Next Steps

After creating these pages:

1. **Delete old PHP templates** (optional, after verifying Gutenberg pages work):
   - `page-about.php`
   - `page-contact.php`
   - `page-faqs.php`

2. **Keep PHP templates** for dynamic pages:
   - `page-reviews.php` (database queries)
   - `page-bookmarks.php` (user authentication)
   - `page-bookings.php` (WooCommerce)
   - `page-profile.php` (user data)

3. **Test thoroughly**:
   - Fill out contact form
   - Navigate between pages
   - Test on mobile devices
   - Check all links work

4. **Go live**:
   - Backup site
   - Update menu
   - Test live site
   - Monitor for issues

---

## Tips for Content Editors

**Editing Gutenberg Pages:**
- Click any block to edit its content
- Drag blocks to reorder them
- Use the **+** button to add new blocks
- Use **Patterns** tab to insert pre-built sections
- Use **List View** (icon in top toolbar) to see all blocks

**Best Practices:**
- Keep headings consistent (H1 for page title, H2 for sections, H3 for subsections)
- Use patterns for consistent styling
- Preview changes before publishing
- Save drafts frequently
- Use revision history if needed

---

**Last Updated**: 2026-02-16
**Version**: 2.0 (Gutenberg Approach)
