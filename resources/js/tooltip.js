const defaultOptions = {
  defaultTemplate: '<div class="v-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
}

export function setTooltipOptions(VTooltip) {
  VTooltip.options = _.merge(VTooltip.options, defaultOptions)
}
