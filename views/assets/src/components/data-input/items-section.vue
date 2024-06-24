<script>

import ItemFields from "@components/form-components/item-fields.vue";

export default {
  components: { ItemFields },
  props: {
    items: {
      type: Object,
      required: true
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      complex: false,
      weight: 0,
      typeKey: {
        3 : "crates",
        2 : "cartons",
        4 : "skids",
        5 : "fiberCases",
        6 : "carpets",
        7 : "misc"
      }
    }
  },
  computed: {
    totalPieces() {
      return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.pcs), 0);
    },
    totalBols() {
      return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.bols), 0);
    },
    totalWeight() {
      if (this.complex) {
        return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.weight), 0);
      } else {
        return this.weight;
      }
    },
    billableWeight() {
      return Math.ceil(this.totalWeight / 100) * 100;
    },
    btnText() {
      return this.complex ? 'Complex' : 'Simple';
    }
  },
  methods: {
    handleUpdate(item) {
      const type = this.typeKey[item.type]
      this.items[type] = item;
      this.$emit('update-items', this.items);
    },
    toggleForm() {
      this.complex = !this.complex;
      const btn = document.querySelector('#toggle-complex');
      btn.classList.toggle('btn-secondary');
      btn.classList.toggle('btn-outline-secondary');
    }
  },
  watch: {
    billableWeight() {
      this.$emit('update-billable-weight', this.billableWeight);
    }
  },
  created() {
    this.complex = this.readOnly;
  }
}
</script>

<template>
  <div class="sl-transaction-items my-2">
    <div class="sl-transaction-items-header mb-2">
      <h4 class="mb-0">Items</h4>
      <button v-if="!readOnly" id="toggle-complex" class="btn btn-sm btn-outline-secondary ms-2" @click="toggleForm">{{ btnText }}</button>
    </div>
    <div v-if="!complex" aria-describedby="Enter the pieces of each item type.">
      <div class="sl-items-header">
        <div class="sl-item-label-box"><label class="ms-2" for="crates" >Crates</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="cartons" >Cartons</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="pcs" >Skids</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="fiberCases" >Fiber Cases</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="carpets" >Carpets</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="misc" >Misc</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="" >Total Pcs</label></div>
      </div>
      <div class="input-group mb-1">
        <input class="form-control" id="crates" type="number" v-model="items['crates'].pcs" />
        <input class="form-control" id="cartons" type="number" v-model="items['cartons'].pcs" />
        <input class="form-control" id="pcs" type="number" v-model="items['skids'].pcs" />
        <input class="form-control" id="fiberCases" type="number" v-model="items['fiberCases'].pcs" />
        <input class="form-control" id="carpets" type="number" v-model="items['carpets'].pcs" />
        <input class="form-control" id="misc" type="number" v-model="items['misc'].pcs" />
        <input class="form-control" id="totalPieces" type="number" v-model="totalPieces" disabled />
      </div>
    </div>
    <div v-if="complex" aria-describedby="A table form to record item details for the transaction.">
        <div class="row">
          <div class="col text-center"></div>
          <div class="col text-center"># Pieces</div>
          <div class="col text-center">BOL Count</div>
          <div class="col text-center">Item Weight</div>
          <div class="col text-center">Notes</div>
          <div class="col text-center">Indiv. Tracking</div>
        </div>
        <ItemFields :read-only="readOnly" :init-item="items['crates']" @update-item="handleUpdate" />
        <ItemFields :read-only="readOnly" :init-item="items['cartons']" @update-item="handleUpdate" />
        <ItemFields :read-only="readOnly" :init-item="items['skids']" @update-item="handleUpdate" />
        <ItemFields :read-only="readOnly" :init-item="items['fiberCases']" @update-item="handleUpdate" />
        <ItemFields :read-only="readOnly" :init-item="items['carpets']" @update-item="handleUpdate" />
        <ItemFields :read-only="readOnly" :init-item="items['misc']" @update-item="handleUpdate" />
        <div class="input-group input-group-sm mb-1">
          <span class="input-group-text sl-item-label-complex">Totals</span>
          <input class="form-control" type="number" v-model="totalPieces" disabled />
          <input class="form-control" type="number" v-model="totalBols" disabled />
          <input class="form-control" type="number" v-model="totalWeight" disabled />
          <input class="form-control sl-items-note" type="text" value="Billable Wgt:" disabled />
          <input class="form-control" type="text" v-model="billableWeight" disabled />
        </div>
      </div>
  </div>
</template>

<style lang="less">
.sl-transaction-items-header {
  display: flex;
  align-items: center;
}
h4 {
  display: inline;
  vertical-align: center;
}
.sl-items-header {
  font-style: normal;
  display: flex;
  align-items: center;
}
.sl-item-label-box {
  flex: 1;
  justify-content: start;
}
.sl-cool-cell {
  border: none;
  border-right: #dddddd 1px solid;
  padding: 0;
}
.sl-items-input{
  width: 100%;
  height: 100%;
  appearance: none;
  -webkit-appearance: none;
  border: none;
  margin: 0;
  padding: 5px;
  background: none;
  font-size: 1rem;
}
.sl-items-reading {
  align-self: center;
  margin: 0 0 0 10px;
  width: 100%;
}
#toggle-complex {
  display: inline;
}
.sl-item-label-complex {
  width: 120px;
}
.sl-items-note {
  width: 200px;
}
.sl-items-spacer {
  width: 120px;
}
</style>
