Custom Tailwind CSS Utility Classes Documentation body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"; background-color: #f8fafc; color: #1a202c; line-height: 1.5; } .container { max-width: 1200px; margin: 0 auto; padding: 2rem; } h1, h2, h3, h4, h5, h6 { color: #2d3748; } h1 { font-size: 2.25rem; margin-bottom: 1rem; } h2 { font-size: 1.5rem; margin-top: 2rem; margin-bottom: 1rem; } h3 { font-size: 1.25rem; margin-top: 1.5rem; margin-bottom: 1rem; } p { margin-bottom: 1rem; } pre { background-color: #edf2f7; padding: 1rem; border-radius: 0.25rem; overflow: auto; } code { font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; background-color: #edf2f7; padding: 0.25rem; border-radius: 0.25rem; } a { color: #3182ce; text-decoration: none; } a:hover { text-decoration: underline; } ul { list-style-type: disc; padding-left: 1.5rem; } .table-container { overflow-x: auto; } table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; } th, td { padding: 0.75rem; border: 1px solid #e2e8f0; } th { background-color: #f7fafc; text-align: left; }

# Custom Tailwind CSS Utility Classes Documentation

This documentation covers the custom utility classes included in the Tailwind CSS file. These utilities are designed to simplify your styling process by providing predefined sizes, padding, margin, grid, flex layouts, positioning, and percentage-based widths and heights.

## Introduction

**Tailwind CSS** is a highly customizable, low-level CSS framework that gives you all of the building blocks you need to build bespoke designs without any annoying opinionated styles you have to fight to override.

**Filament PHP** is a collection of tools for rapidly building beautiful TALL stack applications, designed to work seamlessly with Laravel.

## Contents

*   [Text Sizes](#text-sizes)
*   [Padding](#padding)
*   [Margin](#margin)
*   [Grid Layout](#grid-layout)
*   [Flex Layout](#flex-layout)
*   [Positioning](#positioning)
*   [Width](#width)
*   [Height](#height)

- - -

## Text Sizes

Custom classes for setting text sizes:

```
<!-- Example Usage -->
<p class="txt-size-xl">Extra Large Text</p>
<p class="txt-size-2xl">2XL Text</p>
<p class="txt-size-3xl">3XL Text</p>
<p class="txt-size-4xl">4XL Text</p>
<p class="txt-size-5xl">5XL Text</p>
<p class="txt-size-6xl">6XL Text</p>
<p class="txt-size-7xl">7XL Text</p>
<p class="txt-size-8xl">8XL Text</p>
<p class="txt-size-9xl">9XL Text</p>
```

| Class Name | Description |
| --- | --- |
| `.txt-size-xl` | Extra Large Text |
| `.txt-size-2xl` | 2XL Text |
| `.txt-size-3xl` | 3XL Text |
| `.txt-size-4xl` | 4XL Text |
| `.txt-size-5xl` | 5XL Text |
| `.txt-size-6xl` | 6XL Text |
| `.txt-size-7xl` | 7XL Text |
| `.txt-size-8xl` | 8XL Text |
| `.txt-size-9xl` | 9XL Text |

## Padding

Custom classes for setting padding:

```
<!-- Example Usage -->
<div class="p-size-xl">Extra Large Padding</div>
<div class="px-size-lg">Large Horizontal Padding</div>
<div class="py-size-md">Medium Vertical Padding</div>
```

| Class Name | Description |
| --- | --- |
| `.p-size-xs` | Extra Small Padding |
| `.p-size-sm` | Small Padding |
| `.p-size-md` | Medium Padding |
| `.p-size-lg` | Large Padding |
| `.p-size-xl` | Extra Large Padding |
| `.p-size-2xl` | 2XL Padding |
| `.p-size-3xl` | 3XL Padding |
| `.p-size-4xl` | 4XL Padding |
| `.p-size-5xl` | 5XL Padding |
| `.p-size-6xl` | 6XL Padding |
| `.p-size-7xl` | 7XL Padding |
| `.p-size-8xl` | 8XL Padding |
| `.p-size-9xl` | 9XL Padding |

## Margin

Custom classes for setting margin:

```
<!-- Example Usage -->
<div class="m-size-xl">Extra Large Margin</div>
<div class="mx-size-lg">Large Horizontal Margin</div>
<div class="my-size-md">Medium Vertical Margin</div>
```

| Class Name | Description |
| --- | --- |
| `.m-size-xs` | Extra Small Margin |
| `.m-size-sm` | Small Margin |
| `.m-size-md` | Medium Margin |
| `.m-size-lg` | Large Margin |
| `.m-size-xl` | Extra Large Margin |
| `.m-size-2xl` | 2XL Margin |
| `.m-size-3xl` | 3XL Margin |
| `.m-size-4xl` | 4XL Margin |
| `.m-size-5xl` | 5XL Margin |
| `.m-size-6xl` | 6XL Margin |
| `.m-size-7xl` | 7XL Margin |
| `.m-size-8xl` | 8XL Margin |
| `.m-size-9xl` | 9XL Margin |

## Grid Layout

Custom classes for setting grid layouts:

```
<!-- Example Usage -->
<div class="grid-cols-2">Two Column Grid</div>
<div class="grid-cols-3">Three Column Grid</div>
<div class="grid-cols-4">Four Column Grid</div>
```

| Class Name | Description |
| --- | --- |
| `.grid-cols-1` | One Column Grid |
| `.grid-cols-2` | Two Column Grid |
| `.grid-cols-3` | Three Column Grid |
| `.grid-cols-4` | Four Column Grid |
| `.grid-cols-5` | Five Column Grid |
| `.grid-cols-6` | Six Column Grid |
| `.grid-cols-7` | Seven Column Grid |
| `.grid-cols-8` | Eight Column Grid |
| `.grid-cols-9` | Nine Column Grid |
| `.grid-cols-10` | Ten Column Grid |
| `.grid-cols-11` | Eleven Column Grid |
| `.grid-cols-12` | Twelve Column Grid |

## Flex Layout

Custom classes for setting flex layouts:

```
<!-- Example Usage -->
<div class="flex-col-1">One Item Per Row</div>
<div class="flex-col-2">Two Items Per Row</div>
<div class="flex-col-3">Three Items Per Row</div>
```

| Class Name | Description |
| --- | --- |
| `.flex-col-1` | One Item Per Row |
| `.flex-col-2` | Two Items Per Row |
| `.flex-col-3` | Three Items Per Row |

## Positioning

Custom classes for setting positioning:

```
<!-- Example Usage -->
<div class="top-0">Top 0</div>
<div class="right-0">Right 0</div>
<div class="bottom-0">Bottom 0</div>
<div class="left-0">Left 0</div>
```

| Class Name | Description |
| --- | --- |
| `.top-0` | Top 0 |
| `.right-0` | Right 0 |
| `.bottom-0` | Bottom 0 |
| `.left-0` | Left 0 |

## Width

Custom classes for setting width:

```
<!-- Example Usage -->
<div class="w-1/4">25% Width</div>
<div class="w-1/2">50% Width</div>
<div class="w-3/4">75% Width</div>
```

| Class Name | Description |
| --- | --- |
| `.w-1/4` | 25% Width |
| `.w-1/2` | 50% Width |
| `.w-3/4` | 75% Width |
| `.w-full` | 100% Width |

## Height

Custom classes for setting height:

```
<!-- Example Usage -->
<div class="h-1/4">25% Height</div>
<div class="h-1/2">50% Height</div>
<div class="h-3/4">75% Height</div>
```

| Class Name | Description |
| --- | --- |
| `.h-1/4` | 25% Height |
| `.h-1/2` | 50% Height |
| `.h-3/4` | 75% Height |
| `.h-full` | 100% Height |
