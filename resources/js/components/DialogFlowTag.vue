<template>
  <div>
    <div>Hello</div>
    <div class="model_container">
      <model-viewer
        class="model_class"
        id="modelid"
        src="/storage/cube_model.glb"
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
      console.log(intent);

      const modelViewer = document.querySelector("#modelid");
      modelViewer.cameraOrbit = "45deg 55deg 5m";
    });
  },
};
</script>

<style>
.model_container {
  width: 100%;
  height: 520px;
  margin-left: 40px;
}

.model_class {
  height: 520px;
  width: 400px;
}
</style>