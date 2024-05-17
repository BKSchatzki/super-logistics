<script>

import ItemFields from "@components/form-components/item-fields.vue";

export default {
  components: { ItemFields },
  props: {
    items: {
      type: Object,
      required: true
    },
    specialHandling: {
      type: Boolean,
      required: true
    },
  },
  data () {
    return {
      complex: false
    }
  },
  computed: {
    totalPieces() {
      return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.pieces), 0);
    },
    totalBols() {
      return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.bols), 0);
    },
    totalWeight() {
      return Object.values(this.items).reduce((acc, item) => acc + parseInt(item.weight), 0);
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
      this.items[item.type] = item;
      this.$emit('update-items', this.items);
    },
    toggleForm() {
      this.complex = !this.complex;
      const btn = document.querySelector('#toggle-complex');
      btn.classList.toggle('btn-secondary');
      btn.classList.toggle('btn-outline-secondary');
    }
  }
}
</script>

<template>
  <div class="sl-transaction-items my-2">
    <div class="sl-transaction-items-header mb-2">
      <h4 class="mb-0">Items</h4>
      <button id="toggle-complex" class="btn btn-sm btn-outline-secondary ms-2" @click="toggleForm">{{ btnText }}</button>
      <div class="form-check align-middle ms-2">
        <input class="form-check-input mt-1" type="checkbox" v-model="specialHandling" id="special-handling">
        <label class="form-check-label align-middle" for="special-handling">
          Special Handling
        </label>
      </div>
    </div>
    <div v-if="!complex" aria-describedby="Enter the peices of each item type.">
      <div class="sl-items-header">
        <div class="sl-item-label-box"><label class="ms-2" for="crates" >Crates</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="cartons" >Cartons</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="pieces" >Skids</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="fiberCases" >Fiber Cases</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="carpets" >Carpets</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="misc" >Misc</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="" >Total Pcs</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="totalWeight" >Weight</label></div>
        <div class="sl-item-label-box"><label class="ms-2" for="" >Billable Wgt</label></div>
      </div>
      <div class="input-group mb-1">
        <input class="form-control" id="crates" type="number" v-model="items['crates'].pieces" />
        <input class="form-control" id="cartons" type="number" v-model="items['cartons'].pieces" />
        <input class="form-control" id="pieces" type="number" v-model="items['skids'].pieces" />
        <input class="form-control" id="fiberCases" type="number" v-model="items['fiberCases'].pieces" />
        <input class="form-control" id="carpets" type="number" v-model="items['carpets'].pieces" />
        <input class="form-control" id="misc" type="number" v-model="items['misc'].pieces" />
        <input class="form-control" id="totalPieces" type="number" v-model="totalPieces" disabled />
        <input class="form-control" id="totalWeight" type="number" v-model="totalWeight" />
        <input class="form-control" id="billableWeight" type="number" v-model="billableWeight" disabled />
      </div>
    </div>
    <div v-if="complex" aria-describedby="A table form to record item details for the transaction.">
        <div class="row">
          <div class="sl-items-spacer"></div>
          <div class="col"># Pieces</div>
          <div class="col">BOL Count</div>
          <div class="col">Item Weight</div>
          <div class="col">Notes</div>
          <div class="col">Indiv. Tracking</div>
        </div>
        <ItemFields :init-item="items['crates']" @update-item="handleUpdate" />
        <ItemFields :init-item="items['cartons']" @update-item="handleUpdate" />
        <ItemFields :init-item="items['skids']" @update-item="handleUpdate" />
        <ItemFields :init-item="items['fiberCases']" @update-item="handleUpdate" />
        <ItemFields :init-item="items['carpets']" @update-item="handleUpdate" />
        <ItemFields :init-item="items['misc']" @update-item="handleUpdate" />
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
