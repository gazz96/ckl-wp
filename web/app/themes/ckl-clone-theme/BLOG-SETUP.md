# Blog Implementation - Setup Guide

## ✅ Implementation Complete

All blog functionality has been successfully implemented for CK Langkawi website.

## 📁 Files Created/Modified

### New Files Created:

1. **`page-blog.php`** - Blog listing page template
   - Hero section with image/gradient background
   - Search functionality
   - Blog post grid layout (3 columns on desktop)
   - Pagination
   - Newsletter subscription section

2. **`template-parts/content-blog-card.php`** - Blog post card component
   - Featured image with hover effect
   - Category badges
   - Post title, excerpt
   - Date and reading time
   - "Read" link with arrow icon

3. **`assets/js/blog-page.js`** - Blog JavaScript functionality
   - Search with debouncing
   - Card hover effects
   - Smooth scroll for anchor links
   - Scroll animations
   - Newsletter form handling
   - Share button copy functionality

### Modified Files:

1. **`functions.php`** - Added helper functions
   - `ckl_get_reading_time()` - Calculate reading time for posts
   - `ckl_enqueue_blog_scripts()` - Enqueue blog JavaScript

2. **`single.php`** - Already exists (no changes needed)
   - Has proper single post display
   - Author bio, related posts, comments
   - Share buttons

## 🚀 How to Set Up

### Step 1: Create Blog Page in WordPress

1. Go to **WordPress Admin → Pages → Add New**
2. Title: "Blog"
3. In the right sidebar, under **Page Attributes**, select **Template: Blog**
4. Click **Publish**

### Step 2: Add Blog Posts

1. Go to **Posts → Add New**
2. Add post title and content
3. Set featured image (recommended)
4. Select categories and add tags
5. Click **Publish**

### Step 3: Configure Blog Settings

1. Go to **Settings → Reading**
2. Set "Posts page" to your new Blog page (optional, if you want to use WordPress default)
3. Set "Blog pages show at most" to 12 posts

### Step 4: Add Blog Hero Image (Optional)

To add a custom hero image for the blog page:

1. Place your image at: `/assets/images/blog-hero.jpg`
2. Recommended size: 1920x600 pixels
3. Format: JPG or PNG

If no image is provided, a gradient background will be used.

### Step 5: Test the Blog

1. Visit your blog page at `/blog/`
2. Create a few test posts
3. Test search functionality
4. Verify pagination works
5. Check responsive design (mobile, tablet, desktop)

## 🎨 Features Included

### Blog Listing Page (`/blog/`)

- **Hero Section**:
  - Full-width image or gradient background
  - "Blog" heading
  - Breadcrumb navigation (Home / Blog)

- **Search Section**:
  - Search input with icon
  - Real-time search with debouncing
  - Enter key for immediate search

- **Blog Grid**:
  - Responsive grid (1/2/3 columns based on screen size)
  - Post cards with:
    - Featured image with hover zoom
    - Category badges
    - Post title
    - Excerpt (3 lines)
    - Date and reading time
    - "Read" link with arrow

- **Pagination**:
  - Previous/Next buttons with icons
  - Page numbers

- **Newsletter Section**:
  - Email subscription form
  - Blue background section

### Single Blog Post

- **Hero with Featured Image** or gradient fallback
- **Breadcrumbs**: Home / Blog / Post Title
- **Post Meta**:
  - Author with avatar
  - Publication date
  - Reading time
- **Categories**: Badge links
- **Post Content**: Full content with proper typography
- **Tags**: Tag cloud with links
- **Share Buttons**:
  - Facebook
  - Twitter/X
  - WhatsApp
  - Copy Link
- **Author Bio**: Author info with avatar
- **Related Posts**: 3 related posts from same category
- **Comments**: WordPress comments section
- **Navigation**: Previous/Next post links

## 📱 Responsive Design

- **Mobile (< 768px)**: Single column
- **Tablet (768px - 1024px)**: 2 columns
- **Desktop (> 1024px)**: 3 columns

## 🔍 Search Functionality

The blog includes two search methods:

1. **Server-Side Search** (Default):
   - Press Enter or wait 500ms after typing
   - Page reloads with search results
   - URL includes search query (shareable)

2. **Client-Side Features**:
   - Debounced input (waits 500ms after typing stops)
   - Loading state during search
   - Clear search button

## 📊 Reading Time Calculation

Reading time is automatically calculated based on word count:
- Average reading speed: 200 words per minute
- Minimum: 1 minute
- Displayed as: "X min read"

## 🎯 Customization Options

### Change Primary Color

The blog uses `text-blue-600` and `bg-blue-600` for the primary color. To change:

1. Open `page-blog.php`
2. Find and replace:
   - `blue-600` → your color (e.g., `teal-600`, `emerald-600`)
   - `hover:text-blue-600` → `hover:text-teal-600`

### Adjust Posts Per Page

In `page-blog.php`, line ~23:
```php
'posts_per_page' => 12,  // Change this number
```

### Change Grid Layout

In `page-blog.php`, line ~54:
```php
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
```

Options:
- `lg:grid-cols-2` - 2 columns on desktop
- `lg:grid-cols-4` - 4 columns on desktop

### Modify Search Debounce Time

In `assets/js/blog-page.js`, line ~23:
```javascript
searchTimeout = setTimeout(function() {
    // ...
}, 500);  // Change this (milliseconds)
```

## 🔧 Troubleshooting

### Blog page shows 404

1. Make sure you created a page in WordPress Admin
2. Verify the page template is set to "Blog"
3. Check permalinks: Settings → Permalinks → Save Changes

### Posts not appearing

1. Verify posts have status "Published" (not "Draft")
2. Check if posts are assigned to correct post type (Post, not Vehicle)
3. Clear browser cache

### Featured images not showing

1. Make sure posts have featured images set
2. Check image file permissions
3. Verify thumbnails are generated (Settings → Media)

### Search not working

1. Check browser console for JavaScript errors
2. Verify `blog-page.js` is enqueued
3. Clear cache

### Styling looks wrong

1. Clear browser cache
2. Clear any caching plugins (WP Rocket, W3 Total Cache, etc.)
3. Check Tailwind CSS is loading
4. Verify no plugin conflicts

## 📝 Next Steps

### Optional Enhancements:

1. **Load More Button**: Replace pagination with AJAX load more
2. **Category Filter**: Add category filter buttons
3. **Archive Widget**: Add date-based archive
4. **Popular Posts**: Show most viewed posts
5. **Author Page**: Create dedicated author archive pages
6. **Newsletter Integration**: Connect to Mailchimp/ConvertKit
7. **Social Sharing**: Add more social platforms
8. **Reading Progress**: Add progress bar for long posts
9. **Table of Contents**: Auto-generate for long posts
10. **Related Posts Algorithm**: Improve based on tags/categories

### Performance Optimization:

1. Enable lazy loading for images
2. Implement image optimization (WebP)
3. Use AJAX for search (no page reload)
4. Add caching for queries
5. Minify CSS/JS files

## 🎨 CSS Classes Reference

### Blog Card:
- `bg-white` - White background
- `rounded-lg` - Rounded corners
- `border` - Border
- `hover:shadow-lg` - Shadow on hover
- `transition-shadow` - Smooth transition

### Images:
- `aspect-video` - 16:9 aspect ratio
- `object-cover` - Cover container
- `group-hover:scale-105` - Zoom on hover

### Typography:
- `line-clamp-2` - Limit to 2 lines
- `line-clamp-3` - Limit to 3 lines
- `prose prose-lg` - WordPress content styling

## ✅ Checklist

After setting up, verify:

- [ ] Blog page accessible at `/blog/`
- [ ] Test posts display correctly
- [ ] Search works
- [ ] Pagination functions
- [ ] Featured images show
- [ ] Categories link to archive pages
- [ ] Single post page works
- [ ] Author bio displays
- [ ] Related posts appear
- [ ] Share buttons work
- [ ] Mobile responsive
- [ ] Newsletter form functional
- [ ] No console errors

## 📞 Support

If you encounter any issues:

1. Check WordPress error logs
2. Enable `WP_DEBUG` in `wp-config.php`
3. Review browser console for JavaScript errors
4. Verify all files are uploaded correctly

---

**Implementation Date**: 2026-02-16
**Theme Version**: 1.0.0
**WordPress Version**: 6.0+
**PHP Version**: 7.4+
