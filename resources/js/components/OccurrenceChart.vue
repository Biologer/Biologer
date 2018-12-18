<template>
  <div class="">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600">
      <g>
        <line x1="70" y1="10" x2="70" y2="530" stroke-width="2" stroke="#222222" />
        <path d="M70 7 L65 15 L75 15 Z" />
        <text x="80" y="25" font-size="20" fill="#000000">{{ elevationLabel }} (m)</text>

        <rect x="71" y="30" width="62" height="500" fill="#ffffff"></rect>
        <text x="102" y="560" font-size="25" fill="#000000" text-anchor="middle">I</text>

        <rect x="133" y="30" width="56" height="500" fill="#eeeeee"></rect>
        <text x="161" y="560" font-size="25" fill="#000000" text-anchor="middle">II</text>

        <rect x="189" y="30" width="62" height="500" fill="#ffffff"></rect>
        <text x="220" y="560" font-size="25" fill="#000000" text-anchor="middle">III</text>

        <rect x="251" y="30" width="60" height="500" fill="#eeeeee"></rect>
        <text x="281" y="560" font-size="25" fill="#000000" text-anchor="middle">IV</text>

        <rect x="311" y="30" width="62" height="500" fill="#ffffff"></rect>
        <text x="342" y="560" font-size="25" fill="#000000" text-anchor="middle">V</text>

        <rect x="373" y="30" width="60" height="500" fill="#eeeeee"></rect>
        <text x="403" y="560" font-size="25" fill="#000000" text-anchor="middle">VI</text>

        <rect x="433" y="30" width="62" height="500" fill="#ffffff"></rect>
        <text x="464" y="560" font-size="25" fill="#000000" text-anchor="middle">VII</text>

        <rect x="495" y="30" width="62" height="500" fill="#eeeeee"></rect>
        <text x="526" y="560" font-size="25" fill="#000000" text-anchor="middle">VIII</text>

        <rect x="557" y="30" width="60" height="500" fill="#ffffff"></rect>
        <text x="587" y="560" font-size="25" fill="#000000" text-anchor="middle">IX</text>

        <rect x="617" y="30" width="62" height="500" fill="#eeeeee"></rect>
        <text x="648" y="560" font-size="25" fill="#000000" text-anchor="middle">X</text>

        <rect x="679" y="30" width="60" height="500" fill="#ffffff"></rect>
        <text x="709" y="560" font-size="25" fill="#000000" text-anchor="middle">XI</text>

        <rect x="739" y="30" width="62" height="500" fill="#eeeeee"></rect>
        <text x="770" y="560" font-size="25" fill="#000000" text-anchor="middle">XII</text>

        <text x="400" y="590" font-size="20" fill="#000000" dominant-baseline="middle" text-anchor="middle">{{ monthsLabel }}</text>

        <template v-for="(elevation, index) in negativeElevations">
          <line x1="60" :y1="elevation.y" x2="800" :y2="elevation.y" stroke-width="1" :stroke="(index + 1) % 2 ? '#555555' : '#222222'" />
          <text x="55" :y="elevation.y" font-size="20" fill="#000000" dominant-baseline="central" text-anchor="end">{{ elevation.value }}</text>
        </template>

        <line x1="60" :y1="yForZeroElevation" x2="800" :y2="yForZeroElevation" stroke-width="2" stroke="#222222" />
        <text x="55" :y="yForZeroElevation" font-size="20" fill="#000000" dominant-baseline="central" text-anchor="end">0</text>

        <template v-for="(elevation, index) in positiveElevations">
          <line x1="60" :y1="elevation.y" x2="800" :y2="elevation.y" stroke-width="1" :stroke="(index + 1) % 2 ? '#555555' : '#222222'" />
          <text x="55" :y="elevation.y" font-size="20" fill="#000000" dominant-baseline="central" text-anchor="end">{{ elevation.value }}</text>
        </template>
      </g>

      <g>
        <!--Occurrence -->
        <template v-for="point in preparedPoints">
          <circle :cx="point.x" :cy="point.y" r="7" :fill="point.color" stroke-width="1" stroke="black" />
        </template>
      </g>

      <!-- Current day of year -->
      <line :x1="currentDateLineX" y1="20" :x2="currentDateLineX" y2="528" stroke-width="1" stroke="#00aa00" />
    </svg>

    <div class="flex flex-col is-flex-center">
      <div class="control" v-for="stage in allStages">
        <label class="checkbox">
          <input type="checkbox" name="stages" :value="stage" v-model="visibleStages"> {{ trans(`stages.${stage}`) }} <span :style="optionColor(stage)"></span>
        </label>
      </div>
    </div>
  </div>
</template>

<script>
const TOTAL_YEAR_SPACE = 730
const START_YEAR_FROM = 71

const ELEVATION_START = 529
const ELEVATION_END = 30
const ELEVATION_RANGE = ELEVATION_START - ELEVATION_END

const STAGE_COLORS = {
  egg: 'white',
  larva: 'green',
  pupa: 'yellow',
  juvenile: 'orange',
  adult: 'red'
}

const ALL_STAGES = Object.keys(STAGE_COLORS)

export default {
  name: 'nzOccurrenceChart',

  props: {
    elevationLabel: {
      type: String,
      default: 'Elevation'
    },

    monthsLabel: {
      type: String,
      default: 'Months'
    },

    availableStages: {
      type: Array,
      default: () => []
    },

    data: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      visibleStages: ALL_STAGES
    }
  },

  computed: {
    /**
     * All available stages for the Taxon.
     *
     * @return {Array}
     */
    allStages() {
      return ALL_STAGES.filter(stage => this.availableStages.includes(stage))
    },

    /**
     * Position on X axis of current date.
     *
     * @return {Number}
     */
    currentDateLineX() {
      return this.xForDate(moment())
    },

    /**
     * Data filtered by having supported stage and any value for elevation.
     *
     * @return {Array}
     */
    filteredData() {
      return this.data.filter(item => this.visibleStages.includes(item.stage) && item.elevation !== null)
    },

    /**
     * Data filtered and sorted by date.
     *
     * @return {Array}
     */
    sortedData() {
      const stageOrder = this.stageOrder

      return _.sortBy(this.filteredData, item => item.date)
    },

    /**
     * The highest elevation among given data.
     *
     * @return {Number}
     */
    maxElevation() {
      const max = _.maxBy(this.data, item => item.elevation)

      return max ? max.elevation : 1000
    },

    /**
     * The lowest elevation (depth) among given data.
     *
     * @return {Number}
     */
    minElevation() {
      const min = _.minBy(this.data, item => item.elevation)

      return min ? min.elevation : 0
    },

    /**
     * The highest label for elevation.
     *
     * @return {Number}
     */
    topElevationValue() {
      return parseInt(Math.ceil(this.maxElevation / 1000)) * 1000
    },

    /**
     * The lowest (negative) label for elevation (depth).
     *
     * @return {Number}
     */
    bottomElevationValue() {
      const step = this.topElevationValue / 4

      return this.minElevation >= 0 ? 0 : parseInt(Math.floor(this.minElevation / step)) * step
    },

    /**
     * Data required to draw the circles marking occurrence.
     *
     * @return {Array}
     */
    preparedPoints() {
      return this.sortedData.map(item => ({
        x: this.xForDate(item.date),
        y: this.yForElevation(item.elevation),
        color: STAGE_COLORS[item.stage]
      }))
    },

    /**
     * Line for O on Y axis.
     *
     * @return {Object}
     */
    yForZeroElevation() {
      return this.yForElevation(0)
    },

    /**
     * Lines on the Y axis for labeling depths.
     *
     * @return {Array}
     */
    negativeElevations() {
      const bottomElevationValue = this.bottomElevationValue

      if (bottomElevationValue === 0) {
        return []
      }

      const topElevationValue = this.topElevationValue

      const stepSize = topElevationValue / 4
      const stepCount = Math.abs(bottomElevationValue / stepSize)
      const steps = []

      for (let i = 0; i <= stepCount; i++) {
        const elevation = (i + 1) * -stepSize

        steps.push({
          value: elevation,
          y: this.yForElevation(elevation),
        })
      }

      return steps
    },

    /**
     * Lines on the Y axis for labeling elevation.
     *
     * @return {Array}
     */
    positiveElevations() {
      const topElevationValue = this.topElevationValue

      const steps = []

      for (let i = 0; i < 4; i++) {
        const elevation = parseInt(topElevationValue * (i + 1) / 4)

        steps.push({
          value: elevation,
          y: this.yForElevation(elevation),
        })
      }

      return steps
    }
  },

  methods: {
    /**
     * Calculate progress in a year for given date.
     *
     * @param  {String|Moment}  date
     * @return {Number}
     */
    progressInYear(date) {
      const dateMoment = moment(date)
      const dayOfYear = parseInt(dateMoment.format('DDD'))
      const isLeapYear = dateMoment.isLeapYear()

      return dayOfYear / (isLeapYear ? 366 : 365)
    },

    /**
     * Calculate position on the X axis for given date.
     *
     * @param  {String|Moment}  date
     * @return {Number}
     */
    xForDate(date) {
      return this.progressInYear(moment(date)) * TOTAL_YEAR_SPACE + START_YEAR_FROM
    },

    /**
     * Calculation the position on Y axis for given elevation.
     *
     * @param  {Number}  elevation
     * @return {Number}
     */
    yForElevation(elevation) {
      const topElevationValue = this.topElevationValue
      const bottomElevationValue = this.bottomElevationValue

      const x = (elevation * ELEVATION_RANGE) / (topElevationValue - bottomElevationValue)
      const y = (bottomElevationValue * ELEVATION_RANGE) / (topElevationValue - bottomElevationValue)

      return ELEVATION_START - x + y
    },

    /**
     * Get style settings for circle to visually differentiate checkboxes for stages.
     *
     * @param  {String}  stage
     * @return {Object}
     */
    optionColor(stage) {
      const color = STAGE_COLORS[stage]

      return {
        height: '1rem',
        width: '1rem',
        backgroundColor: color,
        borderRadius: '50%',
        display: 'inline-block',
        border: '1px solid black',
      }
    }
  }
}
</script>
