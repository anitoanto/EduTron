<template>
  <div class="container vmain-c">
    <div class="labels">
      <button
        class="labelBtn"
        @click="makeRotation(40)"
        v-text="'Label 1'"
      />
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
            src="/storage/cube_model.glb"
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
          .on("stream", (stream) => {
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
      videoHere.mute = true;
      this.stream = stream;
      const pusher = this.getPusherInstance();
      this.channel = pusher.subscribe(`presence-${this.randomid}`);
      this.channel.bind(`client-signal-${this.user.id}`, (signal) => {
        const peer = this.getPeer(signal.userId, false);
        peer.signal(signal.data);
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
    makeRotation(x) {
      const modelViewer = document.querySelector("#modelid");
      modelViewer.cameraOrbit = "45deg 55deg 5m";
    },
  },
};
</script>
<style>
.vmain-c {
  position: relative;
}
.video-container {
  width: 100%;
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
  top: 580px;
  left: 25px;
}

.model_container {
  width: 100%;
  height: 520px;
  margin-left: 40px;
}

.model_class {
  height: 520px;
  width: 400px;
}

.labels {
  display: flex;
  flex-direction: row;
  padding-bottom: 10px;
  padding-right: 5px;
}

.labelBtn {
  height: 40px;
}
</style>