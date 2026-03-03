<?php
/**
 * Title: Vehicle Filter Sidebar
 * Slug: ckl-cloner/vehicle-filters
 * Categories: ckl-vehicles
 * Block Types: core/group
 * Description: Sidebar with search, date pickers, and vehicle type filters
 */
?>
<!-- wp:group {"className":"bg-white rounded-lg shadow p-6"} -->
<div class="wp-block-group bg-white rounded-lg shadow p-6">
  <!-- wp:heading {"level":3,"className":"text-xl font-bold mb-6"} -->
  <h3 class="text-xl font-bold mb-6">Filter Vehicles</h3>
  <!-- /wp:heading -->

  <!-- wp:group {"className":"mb-6"} -->
  <div class="wp-block-group mb-6">
    <!-- wp:heading {"level":4,"className":"text-sm font-medium mb-2"} -->
    <h4 class="text-sm font-medium mb-2">Search</h4>
    <!-- /wp:heading -->

    <!-- wp:group {"className":"relative"} -->
    <div class="wp-block-group relative">
      <input type="text" id="search-vehicles" placeholder="Search vehicles..."
             className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent" />
      <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
    </div>
    <!-- /wp:group -->
  </div>
  <!-- /wp:group -->

  <!-- wp:group {"className":"mb-6"} -->
  <div class="wp-block-group mb-6">
    <!-- wp:heading {"level":4,"className":"text-sm font-medium mb-2"} -->
    <h4 class="text-sm font-medium mb-2">Pick-up Date</h4>
    <!-- /wp:heading -->

    <div class="relative">
      <input type="date" id="pickup-date"
             className="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent" />
      <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
    </div>
  </div>
  <!-- /wp:group -->

  <!-- wp:group {"className":"mb-6"} -->
  <div class="wp-block-group mb-6">
    <!-- wp:heading {"level":4,"className":"text-sm font-medium mb-2"} -->
    <h4 class="text-sm font-medium mb-2">Return Date</h4>
    <!-- /wp:heading -->

    <div class="relative">
      <input type="date" id="return-date"
             className="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent" />
      <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
    </div>
  </div>
  <!-- /wp:group -->

  <!-- wp:group -->
  <div class="wp-block-group">
    <!-- wp:heading {"level":4,"className":"text-sm font-medium mb-4"} -->
    <h4 class="text-sm font-medium mb-4">Vehicle Type</h4>
    <!-- /wp:heading -->

    <!-- wp:group {"className":"space-y-2"} -->
    <div class="wp-block-group space-y-2">
      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="sedan" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">Sedan</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="compact" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">Compact</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="mpv" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">MPV</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="luxury_mpv" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">Luxury MPV</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="suv" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">SUV</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="4x4" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">4x4</span>
      </label>

      <label class="flex items-center cursor-pointer">
        <input type="checkbox" value="motorcycle" class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
        <span class="ml-2">Motorcycle</span>
      </label>
    </div>
    <!-- /wp:group -->
  </div>
  <!-- /wp:group -->
</div>
<!-- /wp:group -->
