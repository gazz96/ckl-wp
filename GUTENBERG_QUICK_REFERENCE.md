# Gutenberg Quick Reference Card

## 🎯 Quick Access to Block Patterns

In WordPress Admin → Pages → Add New → **+** → **Patterns** tab

### Hero Sections
- **Page Hero - About** → About Us page header
- **Page Hero - Contact** → Contact Us page header
- **Page Hero - FAQ** → FAQs page header

### Sections
- **Section - Company Info** → Company details with icons
- **Section - Mission Vision** → Mission & Vision columns
- **Section - Core Values** → 4-column values grid
- **Section - Services** → 6 services cards
- **Section - Contact Info** → Contact details
- **Section - CTA** → Call to action with buttons

---

## 📄 Page Creation Summary

### About Us Page
**URL:** `/about`

**Blocks in order:**
1. Pattern: Page Hero - About
2. Heading: "Welcome to CK Langkawi Trip"
3. Columns (50/50):
   - Left: "Our Story" content
   - Right: Pattern: Section - Company Info
4. Pattern: Section - Mission Vision
5. Heading: "Our Core Values"
6. Pattern: Section - Core Values
7. Heading: "Our Services"
8. Pattern: Section - Services
9. Heading: "Why Choose CK Langkawi?"
10. Columns (3): Best Prices, Well Maintained, 24/7 Support
11. Pattern: Section - CTA

**⏱️ Time to build:** ~15 minutes

---

### Contact Us Page
**URL:** `/contact`

**Blocks in order:**
1. Pattern: Page Hero - Contact
2. Columns (50/50):
   - Left: Pattern: Section - Contact Info
   - Right: Contact Form (use plugin or custom HTML)
3. HTML: Google Maps embed (optional)

**⏱️ Time to build:** ~10 minutes

---

### FAQs Page
**URL:** `/faq`

**Blocks in order:**
1. Pattern: Page Hero - FAQ
2. Search block (optional)
3. **Category 1:** Heading + 5 Details blocks
4. **Category 2:** Heading + 5 Details blocks
5. **Category 3:** Heading + 4 Details blocks
6. **Category 4:** Heading + 5 Details blocks
7. Pattern: Section - CTA (edit heading: "Still Have Questions?")

**⏱️ Time to build:** ~20 minutes

---

## 🎨 Design Tokens

### Colors
- **Blue 600**: #2563eb (primary)
- **Blue 700**: #1d4ed8 (hover)
- **Blue 800**: #1e40af (dark)
- **Blue 50**: #eff6ff (light bg)
- **Blue 100**: #dbeafe (accent)
- **White**: #ffffff
- **Gray 50-800**: Various grays

### Typography
- **H1**: 3rem (48px)
- **H2**: 1.875rem (30px)
- **H3**: 1.5rem (24px)
- **Body Large**: 1.25rem (20px)
- **Body**: 1rem (16px)

### Spacing
- **Section padding**: 4rem (64px)
- **Card padding**: 1.5rem (24px)
- **Gap between elements**: 1.5rem (24px)

---

## 🛠️ Recommended Plugins

### Contact Forms
1. **WPForms Lite** (recommended)
   - Free version available
   - Drag & drop builder
   - Email notifications

2. **Contact Form 7**
   - Free, popular
   - Simple shortcode integration
   - Many add-ons available

### Optional Enhancements
- **Yoast SEO** - SEO optimization
- **Smush** - Image optimization
- **WP Super Cache** - Caching

---

## ✅ Verification Checklist

### After Creating Pages:
- [ ] Preview each page
- [ ] Test all links
- [ ] Check mobile responsiveness (use browser DevTools)
- [ ] Submit contact form test
- [ ] Expand/collapse FAQ accordions
- [ ] Add pages to menu
- [ ] Clear cache

### Before Going Live:
- [ ] Backup site
- [ ] Test on mobile devices
- [ ] Test all forms
- [ ] Check page load speed
- [ ] Verify SEO meta tags
- [ ] Test with multiple users

---

## 🚨 Common Issues & Fixes

### Issue: Patterns not showing
**Fix:**
1. Clear WordPress cache
2. Deactivate/reactivate theme
3. Check `/patterns/` folder exists

### Issue: Styles not applying
**Fix:**
1. Clear browser cache (Cmd+Shift+R)
2. Clear WordPress cache
3. Check `style.css` loaded in DevTools

### Issue: Contact form not sending
**Fix:**
1. Check plugin is activated
2. Verify SMTP settings (use WP Mail SMTP plugin if needed)
3. Check spam folder
4. Test with different email

### Issue: Layout broken on mobile
**Fix:**
1. Check column blocks have responsive settings
2. Test on actual device
3. Reduce padding/font sizes
4. Check for fixed widths

---

## 📞 Support

If you encounter issues:
1. Check the detailed guide: `GUTENBERG_PAGES_GUIDE.md`
2. Review WordPress docs: https://wordpress.org/documentation/article/block-editor/
3. Check theme files in `/web/app/themes/ckl-clone-theme/`

---

**Remember:** Users can now edit content in WordPress admin without touching any code! 🎉

**Last Updated**: 2026-02-16
