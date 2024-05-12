registerModule("front-end", "front-end");
import FrontendMenu from "@components/frontend/Menu.vue"

appCommonComponents.push({
  hook: "sl-before-router-view",
  component: "frontend-menu",
  property: FrontendMenu
})
