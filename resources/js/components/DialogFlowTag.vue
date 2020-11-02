<template>
  <div>
    <div class="model_container_alone">
      <model-viewer
        class="model_class_alone"
        id="modelid"
        src="/storage/machine_model_joined.glb"
        shadow-intensity="1"
        camera-controls
        interaction-prompt="none"
      >
      </model-viewer>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    const modelViewer = document.querySelector("#modelid");
    // rope_drum 60deg 74deg 4m
    // main_shaft 1deg 84deg 5m
    // gypsy_head -40deg 84deg 5m
    // rope_drum_band_brake -120deg 78deg 5.3m
    // hydraulic_pump -202deg 100deg 5.6m

    modelViewer.cameraOrbit = "60deg 74deg 7.4m"; //default
    // Initiate the Pusher JS library
    var pusher2 = new Pusher("c9dccf451ae7cd4e6134", {
      authEndpoint: "/home/join/auth",
      cluster: "ap2",
      auth: {
        headers: {
          "X-CSRF-Token": document.head.querySelector('meta[name="csrf-token"]')
            .content,
        },
      },
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel2 = pusher2.subscribe("presence-dialogflow");

    // Bind a function to a Event (the full Laravel class)
    channel2.bind("App\\Events\\DialogFlowWebhook", function (intent) {
      // this is called when the event notification is received...
      console.log(intent.indent_name);
      var r = "nil";
      if (intent.indent_name == "rope_drum") {
        r = "60deg 74deg 4m";
      }
      if (intent.indent_name == "main_shaft") {
        r = "1deg 84deg 5m";
      }
      if (intent.indent_name == "gypsy_head") {
        r = "-40deg 84deg 5m";
      }
      if (intent.indent_name == "band_brake") {
        r = "-120deg 78deg 5.3m";
      }
      if (intent.indent_name == "hydraulic_pump") {
        r = "-202deg 100deg 5.6m";
      }
      if (r != "nil") {
        const modelViewer = document.querySelector("#modelid");
        modelViewer.cameraOrbit = r;
      }
    });
  },
};
</script>

<style>
.model_container_alone {
  width: 100%;
  height: 520px;
  margin-left: 40px;
}

.model_class_alone {
  height: 620px;
  width: 600px;
}
</style>