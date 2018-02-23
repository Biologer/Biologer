<template>
    <b-field :label="label">
        <b-field expanded grouped>
            <b-field expanded :type="errors.has('year') ? 'is-danger': ''"
                :message="errors.has('year') ? errors.first('year'): ''">
                <b-input :placeholder="placeholders.year" :value="year" @input="onYearInput"></b-input>
            </b-field>

            <b-field expanded :type="errors.has('month') ? 'is-danger': ''"
                :message="errors.has('month') ? errors.first('month'): ''">
                <b-select :placeholder="placeholders.month" :value="month" @input="onMonthInput" expanded>
                    <option :value="null"></option>

                    <option v-for="(month, index) in months" :value="(index + 1)" v-text="month"></option>
                </b-select>
            </b-field>

            <b-field expanded :type="errors.has('day') ? 'is-danger': ''"
                :message="errors.has('day') ? errors.first('day'): ''">
                <b-select :placeholder="placeholders.day" :value="day" @input="onDayInput" expanded>
                    <option :value="null"></option>

                    <option v-for="day in days" :value="day" v-text="day"></option>
                </b-select>
            </b-field>
        </b-field>
    </b-field>
</template>

<script>
export default {
  name: 'nzDateInput',

  props: {
    label: {
      type: String,
      default: 'Date'
    },

    dataYear: {
      type: Number,
      default: moment().year()
    },

    dataMonth: {
      type: Number,
      default: moment().month() + 1
    },

    dataDay: {
      type: Number,
      default: moment().date()
    },

    placeholders: {
      type: Object,
      default() {
        return {
          year: 'Year',
          month: 'Month',
          day: 'Day'
        };
      }
    },

    errors: Object
  },

  data() {
    return {
      year: this.dataYear,
      month: this.dataMonth,
      day: this.dataDay,
    }
  },

  computed: {
    now() {
      return moment();
    },

    months() {
      if (!this.year || this.year > moment().year()) return [];

      const isThisYear = this.year === moment().year();

      let months = moment.months();

      if (isThisYear) months.splice(moment().month() + 1);

      return months;
    },

    days() {
      const haveDate = this.year && this.month;

      if (!haveDate) return [];

      const isThisMonth = this.year === moment().year() && (this.month - 1) === moment().month();

      let days = moment({
        year: this.year,
        month: this.month - 1,
      }).daysInMonth();

      if (isThisMonth) days = moment().date();

      return _.range(1, days + 1);
    }
  },

  watch: {
    year() {
      if (this.year > moment().year() || this.months.length < this.month -1)  {
          this.month = null;
      }

      this.truncateDay();
    },

    month() {
      this.truncateDay();
    }
  },

  methods: {
    truncateDay() {
      if (this.days.length < this.day)  {
          this.day = null;
      }
    },

    onYearInput(value) {
      this.year = +value || null;

      this.$emit('year-input', this.year);
    },

    onMonthInput(value) {
      this.month = value;

      this.$emit('month-input', this.month);
    },

    onDayInput(value) {
      this.day = value;

      this.$emit('day-input', this.day);
    }
  }
}
</script>
