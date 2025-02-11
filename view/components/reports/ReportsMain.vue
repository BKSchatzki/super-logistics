<script setup>
import {useAPI} from "@utils/composables/useAPI";
import {Form} from "vee-validate";
import FormTextInput from "@/components/form/FormTextInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import PageTitle from "@/components/general-ui/PageTitle.vue";

const {print} = useAPI();

const printTrailerManifest = (data) => {
  print(data, "reports/trailer-manifest", "Trailer Manifest");
};

const printPalletManifest = (data) => {
  print(data, "reports/pallet-manifest", "Pallet Manifest");
};

</script>

<template>
  <Col>
    <div class="mb-4">
      <PageTitle title="Reports"/>
    </div>
    <Toast/>
    <Panel>
      <Row>
        <Panel header="Trailer Manifest">
          <Form @submit="printTrailerManifest">
            <Col>
              <FormTextInput name="trailer_no" label="Trailer Number"/>
              <FormTextInput name="seal_no" label="Seal Number"/>
              <Button type="submit" label="Print"/>
            </Col>
          </Form>
        </Panel>
        <Panel header="Pallet Manifest">
          <Form @submit="printPalletManifest">
            <Col>
              <FormTextInput name="pallet_no" label="Pallet Number"/>
              <Button type="submit" label="Print"/>
            </Col>
          </Form>
        </Panel>
      </Row>
    </Panel>
  </Col>
</template>

