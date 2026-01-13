# Feature Plan: Frontend Plugin Ecosystem

This document outlines the strategy for creating a WordPress-like plugin ecosystem for the downloadable Nuxt.js frontend starter kits.

## 1. The Core Challenge: Dynamic vs. Build-Time Injection

A traditional PHP plugin system (like WordPress) can load new code dynamically on the server at runtime. Modern JavaScript frontends, for performance and security, are typically bundled at **build time**. This means we cannot dynamically "inject" new, complex features into a live, running Nuxt application.

Our solution, therefore, is not to inject code at runtime, but to **programmatically modify the starter kit's source code on the server** before the user downloads it.

## 2. The "Starter Kit Modification" Model

This model provides a robust and secure way for users to add functionality to their Nuxt projects.

**The Workflow:**
1.  **Plugin Discovery**: The user browses a "Plugin Marketplace" within their main Mintreu dashboard (the Filament backend).
2.  **Installation Request**: The user clicks "Install" on a desired plugin.
3.  **Backend Processing**: A queued job on our server performs the installation:
    a. It fetches the user's latest project configuration.
    b. It reads the selected plugin's `manifest.json` file, which contains instructions for installation.
    c. A **Template Modification Engine** applies these instructions to a fresh copy of the starter kit (e.g., adds an NPM dependency to `package.json`, imports a component into a `.vue` file, and adds the component's tag to the template).
4.  **New Download**: The user is notified that a new, updated version of their project ZIP file is ready for download.
5.  **User Deployment**: The user downloads the new ZIP and redeploys it to their hosting provider (Vercel, Netlify, etc.). The new plugin is now part of their site.

This approach is secure, stable, and works with the modern frontend development lifecycle. The trade-off is that users must re-deploy their site to install or update plugins.

## 3. The Developer's Journey (Creating a Plugin)

To enable a community of developers, we will provide a clear set of guidelines and tools. This will be compiled into a "Developer's Guide" PDF and included with the main starter kit.

### Plugin Structure:
A plugin is essentially a well-packaged set of Vue/Nuxt components, composables, and styles, published as an NPM package (preferably on a private registry we control, or just a Git repository).

The most important part of the plugin is the `plugin-manifest.json` file at its root.

### The `plugin-manifest.json` File:
This file is the blueprint for our Template Modification Engine.

```json
{
  "name": "Mintreu Blog Comments",
  "package_name": "@mintreu/blog-comments",
  "version": "1.0.0",
  "description": "A simple commenting system for blog posts.",
  "author": "Community Dev",
  "target_starter_kit_version": "^1.0.0",
  "installation": [
    {
      "action": "npm_install",
      "package": "@mintreu/blog-comments@^1.0.0",
      "dev": false
    },
    {
      "action": "modify_file",
      "file": "pages/blog/[slug].vue",
      "steps": [
        {
          "type": "add_import",
          "import": "import { CommentsSection } from '@mintreu/blog-comments';"
        },
        {
          "type": "insert_after",
          "marker": "<div class=\"post-content\">",
          "content": "<CommentsSection :post-id=\"post.id\" />"
        }
      ]
    }
  ]
}
```

### Developer Guidelines (To be included in the PDF):
-   **Component Design**: Plugins should be self-contained and expose a clear set of props.
-   **Styling**: Plugins should use standard Tailwind CSS classes to ensure they match the starter kit's design.
-   **Data Fetching**: Plugins should use the pre-configured API client provided in the starter kit to interact with the Mintreu backend.
-   **Manifest Creation**: Detailed documentation on how to create a valid `plugin-manifest.json` file, including all possible `action` types (`npm_install`, `modify_file`, `add_import`, `insert_after`, `replace_content`, etc.).
-   **Submission Process**: How to submit a plugin to our marketplace for review and approval.

## 4. Backend Implementation: The Template Modification Engine

This is the core of the system on our end. It will be a service class in our main Laravel application.

-   **Input**: Takes a user's starter kit template and a `plugin-manifest.json`.
-   **Process**: It will use a combination of file system operations and AST (Abstract Syntax Tree) parsers for JavaScript/Vue files to reliably apply the modifications described in the manifest. Using an AST parser is more robust than simple string replacement.
-   **Output**: Produces a modified directory of the starter kit, ready to be zipped.

## 5. The Future: Dynamic Federation

While the "Starter Kit Modification" model is the best starting point, a more advanced, truly dynamic system could be achieved in the future using **Webpack Module Federation**.

-   **Concept**: The main Nuxt app could be built to dynamically load "micro-frontends" (the plugins) from a remote URL at runtime.
-   **Status**: This is a highly complex architecture. It should be considered a potential V3 feature, not part of the initial plan. We will build our system with this possibility in mind, but we will not implement it now.
