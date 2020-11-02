<template>
  <div class="vmain-c">
    <div class="labels">
      <button class="labelBtn" @click="makeRotation('1deg_84deg_5m')" v-text="'MAIN SHAFT'" />
      <button class="labelBtn" @click="makeRotation('60deg_74deg_4m')" v-text="'ROPE DRUM'" />
      <button class="labelBtn" @click="makeRotation('-120deg_78deg_5.3m')" v-text="'ROPE DRUM HAND BRAKE'" />
      <button class="labelBtn" @click="makeRotation('-202deg_100deg_5.6m')" v-text="'HYDRAULIC PUMP'" />
      <button class="labelBtn" @click="makeRotation('-40deg_84deg_5m')" v-text="'GYPSY HEAD'" />
    </div>
    <div class="video-container" ref="video-container">
      <div>
        <video class="video-here" ref="video-here" autoplay></video>
      </div>

      <div class="video-there-container">
        <video class="video-there" ref="video-there" autoplay></video>
        <div class="model_container">
          <model-viewer
            class="model_class"
            id="modelid"
            src="/storage/machine_model_joined.glb"
            shadow-intensity="1"
            camera-controls
            interaction-prompt="none"
          >
          </model-viewer>
        </div>
      </div>
      <div class="user_btns">
        CONNECT WITH :-
        <div v-for="(name, userId) in others" :key="userId">
          <button @click="startVideoChat(userId)" v-text="`${name}`" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pusher from "pusher-js";
import Peer from "simple-peer";
export default {
  props: ["user", "others", "pusherKey", "pusherCluster", "randomid"],
  data() {
    return {
      channel: null,
      stream: null,
      peers: {},
    };
  },
  mounted() {
    const modelViewer = document.querySelector("#modelid");
    // rope_drum 60deg 74deg 4m
    // main_shaft 1deg 84deg 5m
    // gypsy_head -40deg 84deg 5m
    // rope_drum_band_brake -120deg 78deg 5.3m
    // hydraulic_pump -202deg 100deg 5.6m

    modelViewer.cameraOrbit = "60deg 74deg 7.4m"; //default
    this.setupVideoChat();
  },
  methods: {
    startVideoChat(userId) {
      console.log("Ok Clicked.");
      this.getPeer(userId, true);
    },
    getPeer(userId, initiator) {
      if (this.peers[userId] === undefined) {
        let peer = new Peer({
          initiator,
          stream: this.stream,
          trickle: false,
        });
        peer
          .on("signal", (data) => {
            this.channel.trigger(`client-signal-${userId}`, {
              userId: this.user.id,
              data: data,
            });
          })
          .on("stream", (stream, message) => {
            const videoThere = this.$refs["video-there"];
            videoThere.srcObject = stream;
          })
          .on("close", () => {
            const peer = this.peers[userId];
            if (peer !== undefined) {
              peer.destroy();
            }
            delete this.peers[userId];
          });
        this.peers[userId] = peer;
      }
      return this.peers[userId];
    },
    async setupVideoChat() {
      Pusher.logToConsole = true;
      const stream = await navigator.mediaDevices.getUserMedia({
        video: true,
        audio: true,
      });
      const videoHere = this.$refs["video-here"];
      videoHere.srcObject = stream;
      videoHere.muted = true;
      this.stream = stream;
      const pusher = this.getPusherInstance();
      this.channel = pusher.subscribe(`presence-${this.randomid}`);
      this.channel.bind(`client-signal-${this.user.id}`, (signal) => {
        const peer = this.getPeer(signal.userId, false);
        peer.signal(signal.data);
      });

      // Initiate the Pusher JS library
      var pusher2 = new Pusher(this.pusherKey, {
        authEndpoint: "/home/join/auth",
        cluster: this.pusherCluster,
        auth: {
          headers: {
            "X-CSRF-Token": document.head.querySelector(
              'meta[name="csrf-token"]'
            ).content,
          },
        },
      });

      // Subscribe to the channel we specified in our Laravel Event
      var channel2 = pusher2.subscribe("presence-rotation");

      // Bind a function to a Event (the full Laravel class)
      channel2.bind("App\\Events\\ShareRotation", function (data) {
        // this is called when the event notification is received...
        console.log(data);

        const modelViewer = document.querySelector("#modelid");
        modelViewer.cameraOrbit = data.message.replace(/[_]/g, " ");
      });
    },
    getPusherInstance() {
      return new Pusher(this.pusherKey, {
        authEndpoint: "/home/join/auth",
        cluster: this.pusherCluster,
        auth: {
          headers: {
            "X-CSRF-Token": document.head.querySelector(
              'meta[name="csrf-token"]'
            ).content,
          },
        },
      });
    },
    makeRotation(labelid) {
      $.get("/share/" + labelid, function () {
        console.log("get request sent");
      });
    },
  },
};
</script>
<style>
.vmain-c {
  position: relative;
  margin: 0 150px;
}
.video-container {
  width: 100%;
  position: relative;
  height: 520px;
  border: 2px solid #1e1e1e;
  box-shadow: 1px 1px 1px #9e9e9e;
}
.video-here {
  width: 190px;
  border: 1px solid #000;
  border-radius: 1px;
  z-index: 2;
  position: absolute;
}
.video-there {
  width: auto;
  height: auto;
  z-index: 1;
}

.video-there-container {
  display: flex;
  width: 300px;
  flex-direction: row;
  align-content: flex-start;
}
.user_btns {
  display: inline;
  position: absolute;
  top: 530px;
  left: 25px;
}

.model_container {
  width: 100%;
  height: 520px;
  margin-left: 40px;
}

.model_class {
  height: 520px;
  width: 590px;
}

.labels {
  display: flex;
  flex-direction: row;
  padding-bottom: 10px;
  padding-right: 5px;
}

.labelBtn {
  height: 100%;
  min-width: 100px;
  border: 1px darkblue;
  background-color: lightblue;
  padding: 10px;
  font-size: 19px;
  border-radius: 10px;
  margin-right: 20px;
}
</style>